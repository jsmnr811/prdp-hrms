<?php

namespace App\Livewire\Employee;

use App\Models\User;
use App\Models\WfhTimelog;
use Livewire\Component;
use Livewire\WithPagination;

class AllTimelogs extends Component
{
    use WithPagination;

    public $search;

    public $filterStatus;

    public $filterDateFrom;

    public $filterDateTo;

    public $filterUserId;

    public function mount()
    {
        $this->filterDateFrom = now()->startOfMonth()->toDateString();
        $this->filterDateTo = now()->endOfMonth()->toDateString();
    }

    public function render()
    {
        $user = auth()->user();

        $query = WfhTimelog::with(['user.employee.unit', 'user.employee.office'])

            // Apply filtering based on user's unit/office/office_category hierarchy
            ->whereHas('user.employee', function ($q) use ($user) {
                // If user has a unit, disregard office/office_category and base filtering on unit only
                if ($user->employee && $user->employee->unit_id) {
                    $q->where('unit_id', $user->employee->unit_id);
                } else {
                    // If user has no unit, filter by office_category_id and office_id
                    if ($user->employee) {
                        $q->where('office_category_id', $user->employee->office_category_id)
                            ->where('office_id', $user->employee->office_id);
                    }
                }
            });

        // Apply search filter
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('employee_id', 'like', '%'.$this->search.'%')
                    ->orWhere('username', 'like', '%'.$this->search.'%')
                    ->orWhereHas('employee', function ($eq) {
                        $eq->where('first_name', 'like', '%'.$this->search.'%')
                            ->orWhere('last_name', 'like', '%'.$this->search.'%')
                            ->orWhere('employee_number', 'like', '%'.$this->search.'%');
                    });
            });
        }

        // Apply user filter
        if ($this->filterUserId) {
            $query->where('user_id', $this->filterUserId);
        }

        // Apply status filter
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        // Apply date range filter
        if ($this->filterDateFrom && $this->filterDateTo) {
            $query->whereBetween('date', [$this->filterDateFrom, $this->filterDateTo]);
        } elseif ($this->filterDateFrom) {
            $query->where('date', '>=', $this->filterDateFrom);
        } elseif ($this->filterDateTo) {
            $query->where('date', '<=', $this->filterDateTo);
        }

        $timelogs = $query->orderBy('date', 'desc')
            ->orderBy('time_in', 'desc')
            ->paginate(15);

        // Get users dropdown - filtered by unit/office/office_category hierarchy
        $users = User::whereHas('employee', function ($q) use ($user) {
            // If user has a unit, disregard office/office_category and base filtering on unit only
            if ($user->employee && $user->employee->unit_id) {
                $q->where('unit_id', $user->employee->unit_id);
            } else {
                // If user has no unit, filter by office_category_id and office_id
                if ($user->employee) {
                    $q->where('office_category_id', $user->employee->office_category_id)
                        ->where('office_id', $user->employee->office_id);
                }
            }
        })->role('employee')->get();

        return view('livewire.employee.all-timelogs', [
            'timelogs' => $timelogs,
            'users' => $users,
        ])->layout('components.layouts.app');
    }

    public function updatedFilterDateFrom()
    {
        $this->resetPage();
    }

    public function updatedFilterDateTo()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterUserId()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = null;
        $this->filterStatus = null;
        $this->filterUserId = null;
        $this->filterDateFrom = now()->startOfMonth()->toDateString();
        $this->filterDateTo = now()->endOfMonth()->toDateString();
        $this->resetPage();
    }
}
