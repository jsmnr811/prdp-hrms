<?php

use App\Http\Controllers\WfhTimelogExportController;
use App\Models\ActivityLog;
use App\Livewire\Admin\ActivityLogs as AdminActivityLogs;
use App\Livewire\Admin\AddEmployee;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\EditEmployee;
use App\Livewire\Admin\EmployeeList;
use App\Livewire\Admin\WfhAllTimelogs;
use App\Livewire\Admin\WfhDashboard;
use App\Livewire\Admin\WfhMonitoring;
use App\Livewire\Admin\WfhTimelogs;
use App\Livewire\ChangePassword;
use App\Livewire\Employee\ActivityLogs as EmployeeActivityLogs;
use App\Livewire\Employee\ChangePassword as EmployeeChangePassword;
use App\Livewire\Employee\Dashboard as EmployeeDashboard;
use App\Livewire\Employee\UpdateProfile;
use App\Livewire\ForgotPassword;
use App\Livewire\Login;
use App\Livewire\Register;
use Illuminate\Support\Facades\Route;

// Landing page = Login
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');
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

        // Employee List
        Route::get('/admin/employee-list', EmployeeList::class)->name('admin.employee-list');
        Route::get('/admin/add-employee', AddEmployee::class)->name('admin.add-employee');
        Route::get('/admin/edit-employee/{employeeId}', EditEmployee::class)->name('admin.edit-employee');

        // WFH Timelogs
        Route::get('/admin/wfh-timelogs', WfhTimelogs::class)->name('admin.wfh-timelogs');

        // WFH Dashboard
        Route::get('/admin/wfh-dashboard', WfhDashboard::class)->name('admin.wfh-dashboard');

        // WFH All Timelogs
        Route::get('/admin/wfh-all-timelogs', WfhAllTimelogs::class)->name('admin.wfh-all-timelogs');

        // WFH Monitoring (Map)
        Route::get('/admin/wfh-monitoring', WfhMonitoring::class)->name('admin.wfh-monitoring');

        // Activity Logs
        Route::get('/admin/activity-logs', AdminActivityLogs::class)->name('admin.activity-logs');
    });

    // Employee routes - only for Employees
    Route::middleware('role:employee')->group(function () {
        // Employee Dashboard
        Route::get('/dashboard', EmployeeDashboard::class)->name('dashboard');

        // Update Profile
        Route::get('/update-profile', UpdateProfile::class)->name('update-profile');

        // WFH Timelogs
        Route::get('/wfh-timelogs', WfhTimelogs::class)->name('wfh-timelogs');

        // Change Password
        Route::get('/employee/change-password', EmployeeChangePassword::class)->name('employee.change-password');

        // Activity Logs
        Route::get('/employee/activity-logs', EmployeeActivityLogs::class)->name('employee.activity-logs');
    });

    // Logout
    Route::post('/logout', function () {
        $user = Auth::user();
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'logout',
            'description' => 'User logged out',
            'ip_address' => request()->ip(),
        ]);
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});

// Change Password - no middleware
Route::get('/change-password', ChangePassword::class)->name('change-password');
