<?php

namespace App\Livewire\Employee;

use App\Models\WfhTimelog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount()
    {
        if (! Auth::user()->hasRole('Employee')) {
            abort(403, 'Unauthorized access');
        }
    }

    public function render()
    {
        return view('livewire.employee.dashboard', [
            'stats' => $this->getStats(),
            'recentTimelogs' => $this->getRecentTimelogs(),
        ])->layout('components.layouts.app');
    }

    private function getStats(): array
    {
        $userId = Auth::id();

        return [
            'total_timelogs' => WfhTimelog::where('user_id', $userId)->count(),
            'pending_timelogs' => WfhTimelog::where('user_id', $userId)->pending()->count(),
            'approved_timelogs' => WfhTimelog::where('user_id', $userId)->approved()->count(),
            'rejected_timelogs' => WfhTimelog::where('user_id', $userId)->rejected()->count(),
            'this_month_timelogs' => WfhTimelog::where('user_id', $userId)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->count(),
        ];
    }

    private function getRecentTimelogs()
    {
        return WfhTimelog::where('user_id', Auth::id())
            ->latest('date')
            ->take(5)
            ->get();
    }
}
