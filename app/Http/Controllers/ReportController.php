<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Notifications\NewReportNotification;
use App\Notifications\StatusUpdatedNotification;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // ─── Webhook secret (shared between caller and handler) ───────────────────
    private const WEBHOOK_SECRET = 'bantay-barangay-internal-secret';

    // ─── Dashboard ────────────────────────────────────────────────────────────
    public function dashboard()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $totalReports  = Report::count();
            $pending       = Report::where('status', 'pending')->count();
            $inProgress    = Report::where('status', 'in_progress')->count();
            $resolved      = Report::where('status', 'resolved')->count();
            $recentReports = Report::with('user')->latest()->take(5)->get();
        } else {
            $totalReports  = $user->reports()->count();
            $pending       = $user->reports()->where('status', 'pending')->count();
            $inProgress    = $user->reports()->where('status', 'in_progress')->count();
            $resolved      = $user->reports()->where('status', 'resolved')->count();
            $recentReports = $user->reports()->latest()->take(5)->get();
        }

        return view('dashboard', compact('totalReports', 'pending', 'inProgress', 'resolved', 'recentReports'));
    }

    // ─── Reports list ─────────────────────────────────────────────────────────
    public function index()
    {
        $user    = auth()->user();
        $reports = $user->isAdmin()
            ? Report::with('user')->latest()->get()
            : $user->reports()->latest()->get();

        return view('reports.index', compact('reports'));
    }

    // ─── Create form ──────────────────────────────────────────────────────────
    public function create()
    {
        return view('reports.create');
    }

    // ─── Store new report ─────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category'    => ['required', 'string'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'latitude'    => ['nullable', 'numeric'],
            'longitude'   => ['nullable', 'numeric'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reports', 'public');
        }

        $report = Report::create([
            'user_id'     => auth()->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'category'    => $request->category,
            'image'       => $imagePath,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'status'      => 'pending',
        ]);

        // 🔔 Trigger internal webhook directly (avoid HTTP self-call timeout)
        $this->webhookNotify(new Request([
            'type'      => 'new_report',
            'report_id' => $report->id,
        ]));

        return redirect()->route('reports.index')->with('success', 'Report submitted successfully!');
    }

    // ─── Show single report ───────────────────────────────────────────────────
    public function show($id)
    {
        $user   = auth()->user();
        $report = $user->isAdmin()
            ? Report::with('user')->findOrFail($id)
            : $user->reports()->findOrFail($id);

        return view('reports.show', compact('report'));
    }

    // ─── Admin: update status ─────────────────────────────────────────────────
    public function updateStatus(Request $request, $id)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $request->validate([
            'status' => ['required', 'in:pending,in_progress,resolved'],
        ]);

        $report = Report::findOrFail($id);
        $report->update(['status' => $request->status]);

        // 🔔 Trigger internal webhook directly (avoid HTTP self-call timeout)
        $this->webhookNotify(new Request([
            'type'      => 'status_update',
            'report_id' => $report->id,
        ]));

        return back()->with('success', 'Status updated.');
    }

    // ─── Internal webhook handler ─────────────────────────────────────────────
    public function webhookNotify(Request $request)
    {
        // Skip token check when called directly (no bearer token present)
        if ($request->bearerToken() !== null && $request->bearerToken() !== self::WEBHOOK_SECRET) {
            abort(403, 'Unauthorized webhook call.');
        }

        $type     = $request->input('type');
        $reportId = $request->input('report_id');
        $report   = Report::with('user')->find($reportId);

        if (! $report) {
            return response()->json(['error' => 'Report not found.'], 404);
        }

        if ($type === 'new_report') {
            // Notify all admins
            User::where('role', 'admin')->each(function (User $admin) use ($report) {
                $admin->notify(new NewReportNotification($report));
            });
        } elseif ($type === 'status_update') {
            // Notify the report owner
            $report->user->notify(new StatusUpdatedNotification($report));
        }

        return response()->json(['status' => 'ok']);
    }
}
