<?php

namespace App\Livewire\Employee;

use App\Models\WfhTimelog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyTimelogs extends Component
{
    use WithPagination;

    public $search = '';

    public $status = '';

    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function mount()
    {
        if (! Auth::user()->hasRole('employee')) {
            abort(403, 'Unauthorized access');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $timelogs = WfhTimelog::where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where('accomplishments', 'like', '%'.$this->search.'%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest('date')
            ->paginate($this->perPage);

        return view('livewire.employee.wfh-timelogs', [
            'timelogs' => $timelogs,
        ])->layout('components.layouts.app');
    }
}
