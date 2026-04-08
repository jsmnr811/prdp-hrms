<?php

namespace App\Livewire\Admin;

use App\Jobs\SendWelcomeEmails;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;


class Dashboard extends Component
{
    public function mount()
    {
        if (! Auth::user()->can('view-admin-dashboard')) {
            abort(403, 'Unauthorized access');
        }
    }

    public function confirmSendWelcomeEmails()
    {
        LivewireAlert::title('Send Welcome Emails')
            ->text('Are you sure you want to resend welcome emails to all users?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Send Emails')
            ->withCancelButton('Cancel')
            ->onConfirm('sendWelcomeEmails')
            ->show();
    }

    public function sendWelcomeEmails()
    {
        SendWelcomeEmails::dispatch();
        session()->flash('message', 'Welcome emails have been queued for sending.');
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
        $user = Auth::user();

        // Employee query with visibility applied
        $employeeUsers = Employee::visibleTo($user)
            ->whereHas('user', function ($q) {
                $q->where('status', 1)
                    ->whereHas('roles', function ($q2) {
                        $q2->where('name', 'employee');
                    });
            });

        // User query with visibility applied
        $visibleUsersQuery = User::whereDoesntHave('roles', fn($q) => $q->where('name', 'administrator'))
            ->whereHas('employee', fn($q) => $q->visibleTo($user));

        return [
            'total_employees' => (clone $employeeUsers)->count(),
            'active_employees' => (clone $employeeUsers)->where('employment_status', 'Hired')->count(),

            'total_users' => $visibleUsersQuery->count(),
            'active_users' => (clone $visibleUsersQuery)->where('status', 1)->count(),

            'total_offices' => Office::count(),
            'total_positions' => Position::count(),

            'new_this_month' => (clone $employeeUsers)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),

            'resigned_this_month' => (clone $employeeUsers)
                ->where('employment_status', 'Resigned')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count(),
        ];
    }

    private function getRecentEmployees()
    {
        $user = Auth::user();

        return Employee::with(['office', 'position', 'user'])
            ->visibleTo($user)
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
