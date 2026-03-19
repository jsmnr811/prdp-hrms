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
        // All WFH timelogs (admin can see all)
        $this->totalTimelogs = WfhTimelog::count();
        $this->pendingCount = WfhTimelog::where('status', 'pending')->count();
        $this->completedCount = WfhTimelog::where('status', 'completed')->count();

        // Today's timelogs
        $this->todayTimelogs = WfhTimelog::where('date', now()->toDateString())
            ->with('user')
            ->orderBy('time_in', 'desc')
            ->get();

        // This week's timelogs
        $this->weekTimelogs = WfhTimelog::whereBetween('date', [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString()
        ])->count();

        // This month's timelogs
        $this->monthTimelogs = WfhTimelog::whereBetween('date', [
            now()->startOfMonth()->toDateString(),
            now()->endOfMonth()->toDateString()
        ])->count();
    }

    public function render()
    {
        return view('livewire.admin.wfh-dashboard')
            ->layout('components.layouts.admin');
    }
}
