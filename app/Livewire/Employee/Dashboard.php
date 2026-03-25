<?php

namespace App\Livewire\Employee;

use App\Models\WfhTimelog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount()
    {
        if (! Auth::user()->hasRole('employee')) {
            abort(403, 'Unauthorized access');
        }
    }

    public function render()
    {
        return view('livewire.employee.dashboard', [
            'stats' => $this->getStats(),
            'recentTimelogs' => $this->getRecentTimelogs(),
            'employeeProfile' => $this->getEmployeeProfile(),
            'organizationalInfo' => $this->getOrganizationalInfo(),
        ])->layout('components.layouts.app');
    }

    private function getStats(): array
    {
        $userId = Auth::id();

        $total_timelogs = WfhTimelog::where('user_id', $userId)->count();
        $lastMonth = now()->subMonth();

        // Get all timelogs for total_hours calculation
        $timelogs = WfhTimelog::where('user_id', $userId)->get();
        $total_hours_worked = $timelogs->sum('total_hours');
        $average_hours_per_day = $total_timelogs > 0 ? number_format($total_hours_worked / $total_timelogs, 1) : '0.0';

        return [
            'total_timelogs' => $total_timelogs,
            'pending_timelogs' => WfhTimelog::where('user_id', $userId)->pending()->count(),
            'approved_timelogs' => WfhTimelog::where('user_id', $userId)->approved()->count(),
            'rejected_timelogs' => WfhTimelog::where('user_id', $userId)->rejected()->count(),
            'this_month_timelogs' => WfhTimelog::where('user_id', $userId)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->count(),
            'last_month_timelogs' => WfhTimelog::where('user_id', $userId)
                ->whereMonth('date', $lastMonth->month)
                ->whereYear('date', $lastMonth->year)
                ->count(),
            'total_hours_worked' => $total_hours_worked,
            'average_hours_per_day' => $average_hours_per_day,
        ];
    }

    private function getRecentTimelogs()
    {
        return WfhTimelog::where('user_id', Auth::id())
            ->latest('date')
            ->take(5)
            ->get();
    }

    private function getEmployeeProfile()
    {
        return Auth::user()->employee;
    }

    private function getOrganizationalInfo()
    {
        $employee = $this->getEmployeeProfile();

        return [
            'office' => $employee->office,
            'unit' => $employee->unit,
            'position' => $employee->position,
        ];
    }
}
