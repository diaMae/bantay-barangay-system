<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        return view('dashboard', [
            'totalReports' => $user->reports()->count(),
            'pending'      => $user->reports()->where('status', 'pending')->count(),
            'inProgress'   => $user->reports()->where('status', 'in_progress')->count(),
            'resolved'     => $user->reports()->where('status', 'resolved')->count(),
            'recentReports' => $user->reports()->latest()->take(5)->get(),
        ]);
    }

    public function create()
    {
        return view('report.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => 'required|string',
        ]);

        Report::create([
            'user_id'     => auth()->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'status'      => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Report submitted successfully.');
    }

    public function index()
    {
        $reports = auth()->user()->reports()->latest()->get();
        return view('reports.index', compact('reports'));
    }
}
