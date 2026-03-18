<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use App\Models\Office;
use App\Models\Position;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount()
    {
        if (!auth()->user()->hasRole('administrator')) {
            abort(403, 'Unauthorized access');
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'stats' => $this->getStats(),
            'recentEmployees' => $this->getRecentEmployees(),
            'officeDistribution' => $this->getOfficeDistribution(),
        ])->layout('components.layouts.admin');
    }

private function getStats(): array
{
    // Users with Employee role
    $employeeUsers = User::role('Employee');

    // Join with employees for employment_status and created_at/updated_at
    $employeeUsersQuery = $employeeUsers->whereHas('employee');

    return [
        // Total employees (all users with Employee role)
        'total_employees' => $employeeUsersQuery->count(),

        // Active employees (employment_status = Hired in employee table)
        'active_employees' => $employeeUsersQuery->whereHas('employee', fn($q) => 
            $q->where('employment_status', 'Hired')
        )->count(),

        // Total users (all non-admin users)
        'total_users' => User::whereDoesntHave('roles', fn($q) => $q->where('name', 'Administrator'))->count(),

        // Active users (non-admin, status = 1)
        'active_users' => User::whereDoesntHave('roles', fn($q) => $q->where('name', 'Administrator'))
            ->where('status', 1)
            ->count(),

        'total_offices' => Office::count(),
        'total_positions' => Position::count(),

        // New this month (employees created this month)
        'new_this_month' => $employeeUsersQuery->whereHas('employee', fn($q) =>
            $q->whereMonth('created_at', now()->month)
        )->count(),

        // Resigned this month (employees with employment_status = Resigned)
        'resigned_this_month' => $employeeUsersQuery->whereHas('employee', fn($q) =>
            $q->where('employment_status', 'Resigned')
              ->whereMonth('updated_at', now()->month)
        )->count(),
    ];
}
    private function getRecentEmployees()
    {
        return Employee::with(['office', 'position', 'user'])
            ->whereHas('user', fn($q) => $q->whereDoesntHave('roles', fn($q) => $q->where('name', 'administrator')))
            ->latest()
            ->take(5)
            ->get();
    }

    private function getOfficeDistribution()
    {
        return Office::withCount('employees')
            ->orderBy('employees_count', 'desc')
            ->take(5)
            ->get();
    }
}
