<?php

use App\Http\Controllers\WfhTimelogExportController;
use App\Livewire\Admin\ActivityLogs as AdminActivityLogs;
use App\Livewire\Admin\AddEmployee;
use App\Livewire\Admin\ClusterList;
use App\Livewire\Admin\CreatePermission;
use App\Livewire\Admin\CreateRole;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\EditEmployee;
use App\Livewire\Admin\EditPermission;
use App\Livewire\Admin\EditRole;
use App\Livewire\Admin\EmployeeList;
use App\Livewire\Admin\RegionList;
use App\Livewire\Admin\RolePermissionManagement;
use App\Livewire\Admin\WfhAllTimelogs;
use App\Livewire\Admin\WfhDashboard;
use App\Livewire\Admin\WfhMonitoring;
use App\Livewire\Admin\WfhTimelogs;
use App\Livewire\Admin\ActiveSessions;
use App\Livewire\ChangePassword;
use App\Livewire\Employee\ActivityLogs as EmployeeActivityLogs;
use App\Livewire\Employee\AllTimelogs;
use App\Livewire\Employee\ChangePassword as EmployeeChangePassword;
use App\Livewire\Employee\Dashboard as EmployeeDashboard;
use App\Livewire\Employee\UpdateProfile;
use App\Livewire\ForgotPassword;
use App\Livewire\Login;
use App\Livewire\Register;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Route;

// Landing page = Login
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->can('view-admin-dashboard')) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('dashboard');
        }
    }

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
    Route::get('/timelogs/export', [WfhTimelogExportController::class, 'exportPdf'])->name('timelogs.export')->middleware('permission:export-reports');

    // Admin dashboard
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard')->middleware('permission:view-admin-dashboard');

    // Employee List
    Route::get('/admin/employee-list', EmployeeList::class)->name('admin.employee-list')->middleware('permission:view-employees');
    Route::get('/admin/add-employee', AddEmployee::class)->name('admin.add-employee')->middleware('permission:create-employees');
    Route::get('/admin/edit-employee/{employeeId}', EditEmployee::class)->name('admin.edit-employee')->middleware('permission:edit-employees');

    // Cluster List
    Route::get('/admin/clusters', ClusterList::class)->name('admin.clusters')->middleware('permission:manage-clusters');

    // Region List
    Route::get('/admin/regions', RegionList::class)->name('admin.regions')->middleware('permission:manage-regions');

    // WFH Timelogs
    Route::get('/admin/wfh-timelogs', WfhTimelogs::class)->name('admin.wfh-timelogs')->middleware('permission:manage-timelogs');

    // WFH Dashboard
    Route::get('/admin/wfh-dashboard', WfhDashboard::class)->name('admin.wfh-dashboard')->middleware('permission:view-admin-dashboard');

    // WFH All Timelogs
    Route::get('/admin/wfh-all-timelogs', WfhAllTimelogs::class)->name('admin.wfh-all-timelogs')->middleware('permission:view-admin-dashboard');

    // WFH Monitoring (Map)
    Route::get('/admin/wfh-monitoring', WfhMonitoring::class)->name('admin.wfh-monitoring')->middleware('permission:view-admin-dashboard');

    // Active Sessions Management
    Route::get('/admin/active-sessions', ActiveSessions::class)->name('admin.active-sessions')->middleware('permission:manage-sessions');

    // Activity Logs
    Route::get('/admin/activity-logs', AdminActivityLogs::class)->name('admin.activity-logs');

    // Role & Permission Management
    Route::get('/admin/role-permission-management', RolePermissionManagement::class)->name('admin.role-permission-management')->middleware('permission:manage-roles-permissions');
    Route::get('/admin/roles/create', CreateRole::class)->name('admin.roles.create')->middleware('permission:manage-roles-permissions');
    Route::get('/admin/roles/{role}/edit', EditRole::class)->name('admin.roles.edit')->middleware('permission:manage-roles-permissions');
    Route::get('/admin/permissions/create', CreatePermission::class)->name('admin.permissions.create')->middleware('permission:manage-roles-permissions');
    Route::get('/admin/permissions/{permission}/edit', EditPermission::class)->name('admin.permissions.edit')->middleware('permission:manage-roles-permissions');

    // Employee routes - only for Employees
    // Employee Dashboard
    Route::get('/dashboard', EmployeeDashboard::class)->name('dashboard')->middleware('permission:view-dashboard');

    // Update Profile
    Route::get('/update-profile', UpdateProfile::class)->name('update-profile')->middleware('permission:edit-profile');

    // WFH Timelogs
    Route::get('/wfh-timelogs', WfhTimelogs::class)->name('wfh-timelogs')->middleware('permission:manage-timelogs');

    // All Employee Timelogs
    Route::get('/all-timelogs', AllTimelogs::class)->name('all-timelogs')->middleware('permission:view-timelogs');

    // Change Password
    Route::get('/employee/change-password', EmployeeChangePassword::class)->name('employee.change-password');

    // Activity Logs
    Route::get('/employee/activity-logs', EmployeeActivityLogs::class)->name('employee.activity-logs');

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
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');

    // Impersonate routes
    Route::impersonate();

    Route::get('/logout', function () {
        return redirect()->route('logout');
    });
});

// Change Password - no middleware
Route::get('/change-password', ChangePassword::class)->name('change-password');
