<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Cluster;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ClusterList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    // Modal states
    public $showAddModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form fields
    public $clusterId;
    public $name;
    public $description;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount()
    {
        if (!Auth::user()->hasRole('administrator')) {
            abort(403, 'Unauthorized access');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:clusters,name' . ($this->clusterId ? ',' . $this->clusterId : ''),
            'description' => 'nullable|string',
        ];
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->showAddModal = true;
    }

    public function openEditModal($clusterId)
    {
        $cluster = Cluster::findOrFail($clusterId);
        $this->clusterId = $cluster->id;
        $this->name = $cluster->name;
        $this->description = $cluster->description;
        $this->showEditModal = true;
    }

    public function closeModals()
    {
        $this->showAddModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->clusterId = null;
        $this->name = '';
        $this->description = '';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        if ($this->clusterId) {
            $cluster = Cluster::findOrFail($this->clusterId);
            $cluster->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'edit_cluster',
                'description' => "Edited cluster \"{$cluster->name}\"",
                'ip_address' => request()->ip(),
            ]);

            session()->flash('message', 'Cluster updated successfully.');

            // Close modal via Flux
            Flux::modal('edit-cluster-modal')->close();
        } else {
            $cluster = Cluster::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'add_cluster',
                'description' => "Added cluster \"{$cluster->name}\"",
                'ip_address' => request()->ip(),
            ]);

            session()->flash('message', 'Cluster added successfully.');

            // Close modal via Flux
            Flux::modal('add-cluster-modal')->close();
        }

        // Reset form and validation
        $this->resetForm();
    }

    public function delete($clusterId)
    {
        $this->clusterId = $clusterId;
        $this->showDeleteModal = true;
    }

    public function confirmedDelete()
    {
        $cluster = Cluster::findOrFail($this->clusterId);
        $clusterName = $cluster->name;

        $cluster->delete();

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_cluster',
            'description' => "Deleted cluster \"{$clusterName}\"",
            'ip_address' => request()->ip(),
        ]);

        session()->flash('message', 'Cluster deleted successfully.');

        // Close modal via Flux
        Flux::modal('delete-cluster-modal')->close();

        $this->closeModals();
    }

    public function render()
    {
        $clusters = Cluster::when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.cluster-list', [
            'clusters' => $clusters,
        ])->layout('components.layouts.admin');
    }
}
