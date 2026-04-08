<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogs extends Component
{
    use WithPagination;

    public $filterUser;
    public $filterAction;
    public $filterDateFrom;
    public $filterDateTo;

    public $showDescriptionModal = false;
    public $selectedLog;

    protected $queryString = [
        'filterUser' => ['except' => ''],
        'filterAction' => ['except' => ''],
        'filterDateFrom' => ['except' => ''],
        'filterDateTo' => ['except' => ''],
    ];

    public function mount()
    {
         if (! Auth::user()->can('view-admin-activity-logs')) {
            abort(403, 'Unauthorized access');
        }

        $this->filterDateFrom = now()->startOfMonth()->toDateString();
        $this->filterDateTo = now()->endOfMonth()->toDateString();
    }

    public function render()
    {
        $query = ActivityLog::with('user', 'affectedUser', 'affectedEmployee');

        // Apply filters
        if ($this->filterUser) {
            $query->where('user_id', $this->filterUser);
        }

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
        $users = \App\Models\User::orderBy('username')->get();
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('livewire.admin.activity-logs', [
            'activityLogs' => $activityLogs,
            'users' => $users,
            'actions' => $actions,
        ])->layout('components.layouts.admin');
    }

    public function clearFilters()
    {
        $this->reset(['filterUser', 'filterAction', 'filterDateFrom', 'filterDateTo']);
        $this->filterDateFrom = now()->startOfMonth()->toDateString();
        $this->filterDateTo = now()->endOfMonth()->toDateString();
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
