<?php

namespace App\Livewire\Admin;

use App\Models\WfhTimelog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WfhDashboard extends Component
{
    public $totalTimelogs;
    public $pendingCount;
    public $completedCount;
    public $todayTimelogs;
    public $weekTimelogs;
    public $monthTimelogs;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $user = auth()->user();

        // Base query with visibility scope
        $baseQuery = WfhTimelog::query()
            ->whereHas('user.employee', function ($q) use ($user) {
                $q->visibleTo($user);
            });

        // Counts
        $this->totalTimelogs = (clone $baseQuery)->count();

        $this->pendingCount = (clone $baseQuery)
            ->where('status', 'pending')
            ->count();

        $this->completedCount = (clone $baseQuery)
            ->where('status', 'completed')
            ->count();

        // Today's timelogs
        $this->todayTimelogs = (clone $baseQuery)
            ->where('date', now()->toDateString())
            ->with('user.employee')
            ->orderBy('time_in', 'desc')
            ->get();

        // This week's timelogs
        $this->weekTimelogs = (clone $baseQuery)
            ->whereBetween('date', [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString()
            ])
            ->count();

        // This month's timelogs
        $this->monthTimelogs = (clone $baseQuery)
            ->whereBetween('date', [
                now()->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString()
            ])
            ->count();
    }

    public function render()
    {
        return view('livewire.admin.wfh-dashboard')
            ->layout('components.layouts.admin');
    }
}
