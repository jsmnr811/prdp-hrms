<?php

namespace App\Livewire\Employee;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogs extends Component
{
    use WithPagination;

    public $filterAction;
    public $filterDateFrom;
    public $filterDateTo;

    protected $queryString = [
        'filterAction' => ['except' => ''],
        'filterDateFrom' => ['except' => ''],
        'filterDateTo' => ['except' => ''],
    ];

    public function mount()
    {
        if (!Auth::user()->hasRole('employee')) {
            abort(403, 'Unauthorized access');
        }

        $this->filterDateFrom = now()->startOfMonth()->toDateString();
        $this->filterDateTo = now()->endOfMonth()->toDateString();
    }

    public function render()
    {
        $query = ActivityLog::where('user_id', Auth::id());

        // Apply filters
        if ($this->filterAction) {
            $query->where('action', $this->filterAction);
        }

        if ($this->filterDateFrom && $this->filterDateTo) {
            $query->whereBetween('created_at', [$this->filterDateFrom . ' 00:00:00', $this->filterDateTo . ' 23:59:59']);
        } elseif ($this->filterDateFrom) {
            $query->where('created_at', '>=', $this->filterDateFrom . ' 00:00:00');
        } elseif ($this->filterDateTo) {
            $query->where('created_at', '<=', $this->filterDateTo . ' 23:59:59');
        }

        $activityLogs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $actions = ActivityLog::where('user_id', Auth::id())->select('action')->distinct()->pluck('action');

        return view('livewire.employee.activity-logs', [
            'activityLogs' => $activityLogs,
            'actions' => $actions,
        ])->layout('components.layouts.app');
    }

    public function clearFilters()
    {
        $this->reset(['filterAction', 'filterDateFrom', 'filterDateTo']);
        $this->filterDateFrom = now()->startOfMonth()->toDateString();
        $this->filterDateTo = now()->endOfMonth()->toDateString();
    }
}
