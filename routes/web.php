<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ReportController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Internal webhook endpoint (no auth middleware — secured by bearer token)
Route::post('/webhook/notify', [ReportController::class, 'webhookNotify'])
    ->name('webhook.notify');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/report/create', [ReportController::class, 'create'])->name('report.create');
    Route::post('/report/store', [ReportController::class, 'store'])->name('report.store');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::patch('/reports/{id}/status', [ReportController::class, 'updateStatus'])->name('reports.updateStatus');

    Route::post('/notifications/mark-read', function () {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return back();
    })->name('notifications.markRead');
});

require __DIR__.'/auth.php';
