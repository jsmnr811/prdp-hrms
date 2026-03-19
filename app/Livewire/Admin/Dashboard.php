<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use App\Models\Office;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount()
    {
        if (! Auth::user()->hasRole('administrator')) {
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
    // Base query for employee users
    $employeeUsers = User::role('Employee')
        ->whereHas('employee')
        ->where('status', 1);

    return [
        // Total employees
        'total_employees' => (clone $employeeUsers)->count(),

        // Active employees (Hired)
        'active_employees' => (clone $employeeUsers)
            ->whereHas('employee', fn($q) => $q->where('employment_status', 'Hired'))
            ->count(),

        // Total users (excluding admin)
        'total_users' => User::whereDoesntHave('roles', fn($q) => $q->where('name', 'Administrator'))
            ->count(),

        // Active users (excluding admin)
        'active_users' => User::whereDoesntHave('roles', fn($q) => $q->where('name', 'Administrator'))
            ->where('status', 1)
            ->count(),

        'total_offices' => Office::count(),
        'total_positions' => Position::count(),

        // New this month
        'new_this_month' => (clone $employeeUsers)
            ->whereHas('employee', fn($q) =>
                $q->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year)
            )
            ->count(),

        // Resigned this month
        'resigned_this_month' => (clone $employeeUsers)
            ->whereHas('employee', fn($q) =>
                $q->where('employment_status', 'Resigned')
                  ->whereMonth('updated_at', now()->month)
                  ->whereYear('updated_at', now()->year)
            )
            ->count(),
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
