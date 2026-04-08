<?php

namespace App\Livewire\Admin;

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
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('employee_id', 'like', '%' . $this->search . '%');
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

        // ✅ Also filter users dropdown using same scope
        $users = User::whereHas('employee', function ($q) use ($user) {
            $q->visibleTo($user);
        })->role('employee')->get();

        return view('livewire.admin.wfh-all-timelogs', [
            'timelogs' => $timelogs,
            'users' => $users,
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

    public function clearFilters()
    {
        $this->search = null;
        $this->filterStatus = null;
        $this->filterUserId = null;
        $this->filterDateFrom = null;
        $this->filterDateTo = null;
        $this->resetPage();
    }
}
