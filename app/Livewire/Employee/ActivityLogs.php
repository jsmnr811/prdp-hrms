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

    public $showDescriptionModal = false;

    public $selectedLog;

    protected $queryString = [
        'filterAction' => ['except' => ''],
        'filterDateFrom' => ['except' => ''],
        'filterDateTo' => ['except' => ''],
    ];

    public function mount()
    {
        if (! Auth::user()->can('view-activity-logs')) {
            abort(403, 'Unauthorized access');
        }

        $this->filterDateFrom = now()->startOfMonth()->toDateString();
        $this->filterDateTo = now()->endOfMonth()->toDateString();
    }

    public function render()
    {
        $user = Auth::user();

        // ✅ MAIN QUERY (FIXED)
        $query = ActivityLog::with(['user', 'affectedUser', 'affectedEmployee'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id) // own actions
                    ->orWhere('affected_user_id', $user->id); // admin actions

                if ($user->employee) {
                    $q->orWhere('affected_employee_id', $user->employee->id);
                }
            });

        // ✅ FILTER: action
        if ($this->filterAction) {
            $query->where('action', $this->filterAction);
        }

        // ✅ FILTER: date
        if ($this->filterDateFrom && $this->filterDateTo) {
            $query->whereBetween('created_at', [
                $this->filterDateFrom.' 00:00:00',
                $this->filterDateTo.' 23:59:59',
            ]);
        } elseif ($this->filterDateFrom) {
            $query->where('created_at', '>=', $this->filterDateFrom.' 00:00:00');
        } elseif ($this->filterDateTo) {
            $query->where('created_at', '<=', $this->filterDateTo.' 23:59:59');
        }

        // ✅ PAGINATION
        $activityLogs = $query->orderBy('created_at', 'desc')->paginate(20);

        // ✅ ACTION FILTER OPTIONS (FIXED TOO)
        $actions = ActivityLog::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('affected_user_id', $user->id);

            if ($user->employee) {
                $q->orWhere('affected_employee_id', $user->employee->id);
            }
        })
            ->select('action')
            ->distinct()
            ->pluck('action');

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

        $this->resetPage(); // ✅ important for pagination
    }

    public function showFullDescription($logId)
    {
        $this->selectedLog = ActivityLog::with('user', 'affectedUser', 'affectedEmployee')->find($logId);
        $this->showDescriptionModal = true;
    }

    public function closeDescriptionModal()
    {
        $this->showDescriptionModal = false;
        $this->selectedLog = null;
    }
}
