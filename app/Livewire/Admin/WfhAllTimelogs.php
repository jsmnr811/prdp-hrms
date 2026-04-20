<?php

namespace App\Livewire\Admin;

use App\Models\Office;
use App\Models\OfficeCategory;
use App\Models\Unit;
use App\Models\User;
use App\Models\WfhTimelog;
use Livewire\Component;
use Livewire\WithPagination;

class WfhAllTimelogs extends Component
{
    use WithPagination;

    public $search;

    public $filterStatus;

    public $filterDateFrom;

    public $filterDateTo;

    public $filterUserId;

    public $filterOfficeCategoryId;

    public $filterOfficeId;

    public $filterUnitId;

    public function mount()
    {
        $this->filterDateFrom = now()->startOfMonth()->toDateString();
        $this->filterDateTo = now()->endOfMonth()->toDateString();
    }

    public function render()
    {
        $user = auth()->user();

        $query = WfhTimelog::with('user.employee')

            // ✅ Apply visibility scope here
            ->whereHas('user.employee', function ($q) use ($user) {
                $q->visibleTo($user);
            });

        // Apply search filter
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('employee_id', 'like', '%'.$this->search.'%')
                    ->orWhereHas('employee', function ($sub) {
                        $sub->where('first_name', 'like', '%'.$this->search.'%')
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

        // Apply office category filter
        if ($this->filterOfficeCategoryId) {
            $query->whereHas('user.employee', function ($q) {
                $q->where('office_category_id', $this->filterOfficeCategoryId);
            });
        }

        // Apply office filter
        if ($this->filterOfficeId) {
            $query->whereHas('user.employee', function ($q) {
                $q->where('office_id', $this->filterOfficeId);
            });
        }

        // Apply unit filter
        if ($this->filterUnitId) {
            $query->whereHas('user.employee', function ($q) {
                $q->where('unit_id', $this->filterUnitId);
            });
        }

        // Apply date range filter
        if ($this->filterDateFrom && $this->filterDateTo) {
            $query->whereDate('date', '>=', $this->filterDateFrom)
                ->whereDate('date', '<=', $this->filterDateTo);
        } elseif ($this->filterDateFrom) {
            $query->whereDate('date', '>=', $this->filterDateFrom);
        } elseif ($this->filterDateTo) {
            $query->whereDate('date', '<=', $this->filterDateTo);
        }

        $timelogs = $query->orderBy('date', 'desc')
            ->orderBy('time_in', 'desc')
            ->paginate(15);

        // ✅ Also filter users dropdown using same scope
        $users = User::whereHas('employee', function ($q) use ($user) {
            $q->visibleTo($user);
        })->role('employee')->get();

        // Get office categories, offices, units visible to user
        $officeCategories = OfficeCategory::whereHas('employees', function ($q) use ($user) {
            $q->visibleTo($user);
        })->distinct()->get();

        $offices = Office::whereHas('employees', function ($q) use ($user) {
            $q->visibleTo($user);
        })->distinct()->get();

        $units = Unit::whereHas('employees', function ($q) use ($user) {
            $q->visibleTo($user);
        })->distinct()->get();

        return view('livewire.admin.wfh-all-timelogs', [
            'timelogs' => $timelogs,
            'users' => $users,
            'officeCategories' => $officeCategories,
            'offices' => $offices,
            'units' => $units,
        ])->layout('components.layouts.admin');
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

    public function updatedFilterOfficeCategoryId()
    {
        $this->resetPage();
    }

    public function updatedFilterOfficeId()
    {
        $this->resetPage();
    }

    public function updatedFilterUnitId()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = null;
        $this->filterStatus = null;
        $this->filterUserId = null;
        $this->filterOfficeCategoryId = null;
        $this->filterOfficeId = null;
        $this->filterUnitId = null;
        $this->filterDateFrom = null;
        $this->filterDateTo = null;
        $this->resetPage();
    }
}
