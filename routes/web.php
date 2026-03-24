<?php

use App\Http\Controllers\WfhTimelogExportController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\WfhAllTimelogs;
use App\Livewire\Admin\WfhDashboard;
use App\Livewire\Admin\WfhMonitoring;
use App\Livewire\Admin\WfhTimelogs;
use App\Livewire\ChangePassword;
use App\Livewire\Employee\Dashboard as EmployeeDashboard;
use App\Livewire\ForgotPassword;
use App\Livewire\Login;
use App\Livewire\Register;
use Illuminate\Support\Facades\Route;

// Landing page = Login
Route::get('/', Login::class)->name('home');
Route::get('/login', Login::class)->name('login')->middleware('guest');

// Registration
Route::get('/register', Register::class)->name('register')->middleware('guest');

// Password reset routes
Route::get('/forgot-password', ForgotPassword::class)->middleware('guest')->name('password.request');

// Protected routes
Route::middleware(['auth'])->group(function () {

    // WFH Timelog Export PDF - accessible to all authenticated users for single user export, admins for all
    Route::get('/timelogs/export', [WfhTimelogExportController::class, 'exportPdf'])->name('timelogs.export');

    // Admin routes - only for Administrators
    Route::middleware('role:administrator')->group(function () {
        // Admin dashboard
        Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');

        // WFH Timelogs
        Route::get('/admin/wfh-timelogs', WfhTimelogs::class)->name('admin.wfh-timelogs');

        // WFH Dashboard
        Route::get('/admin/wfh-dashboard', WfhDashboard::class)->name('admin.wfh-dashboard');

        // WFH All Timelogs
        Route::get('/admin/wfh-all-timelogs', WfhAllTimelogs::class)->name('admin.wfh-all-timelogs');

        // WFH Monitoring (Map)
        Route::get('/admin/wfh-monitoring', WfhMonitoring::class)->name('admin.wfh-monitoring');
    });

    // Employee routes - only for Employees
    Route::middleware('role:employee')->group(function () {
        // Employee Dashboard
        Route::get('/dashboard', EmployeeDashboard::class)->name('dashboard');

        // WFH Timelogs
        Route::get('/wfh-timelogs', WfhTimelogs::class)->name('wfh-timelogs');
    });

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});

// Change Password - no middleware
Route::get('/change-password', ChangePassword::class)->name('change-password');
