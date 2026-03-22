<?php

use App\Http\Controllers\WfhTimelogExportController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\WfhTimelogs;
use App\Livewire\Admin\WfhDashboard;
use App\Livewire\Admin\WfhAllTimelogs;
use App\Livewire\Admin\WfhMonitoring;
use App\Livewire\Login;
use App\Livewire\Register;
use Illuminate\Support\Facades\Route;

// Landing page = Login
Route::get('/', Login::class)->name('login')->middleware('guest');
Route::get('/login', Login::class)->name('login')->middleware('guest');

// Registration
Route::get('/register', Register::class)->name('register')->middleware('guest');

// Password reset routes
Route::get('/forgot-password', function () {
    return redirect('/')->with('status', 'Please use the login page.');
})->middleware('guest')->name('password.request');

// Protected routes
Route::middleware(['auth'])->group(function () {

    // Admin dashboard - accessible to any authenticated user for testing
    Route::get('/admin', AdminDashboard::class)->name('admin.dashboard');

    // WFH Timelogs
    Route::get('/admin/wfh-timelogs', WfhTimelogs::class)->name('admin.wfh-timelogs');

    // WFH Dashboard
    Route::get('/admin/wfh-dashboard', WfhDashboard::class)->name('admin.wfh-dashboard');

    // WFH All Timelogs
    Route::get('/admin/wfh-all-timelogs', WfhAllTimelogs::class)->name('admin.wfh-all-timelogs');

    // WFH Monitoring (Map)
    Route::get('/admin/wfh-monitoring', WfhMonitoring::class)->name('admin.wfh-monitoring');

    // WFH Timelog Export PDF
    Route::get('/admin/wfh-timelogs/export', [WfhTimelogExportController::class, 'exportPdf'])->name('admin.wfh-timelogs.export');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
