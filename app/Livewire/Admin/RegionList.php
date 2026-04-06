<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Cluster;
use App\Models\Region;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class RegionList extends Component
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
    public $selectedRegion;
    public $cluster_id;
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
        \Log::info('RegionList updatingSearch: ' . $this->search);
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
            'cluster_id' => 'required|exists:clusters,id',
            'name' => 'required|string|max:255|unique:regions,name' . ($this->selectedRegion ? ',' . $this->selectedRegion : ''),
            'description' => 'nullable|string',
        ];
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->showAddModal = true;
    }

    public function openEditModal($regionId)
    {
        $region = Region::findOrFail($regionId);
        $this->selectedRegion = $region->id;
        $this->cluster_id = $region->cluster_id;
        $this->name = $region->name;
        $this->description = $region->description;
        $this->showEditModal = true;
    }

    public function delete($regionId)
    {
        $this->selectedRegion = $regionId;
        $this->showDeleteModal = true;
    }

    public function confirmedDelete()
    {
        $region = Region::findOrFail($this->selectedRegion);
        $regionName = $region->name;

        $region->delete();

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_region',
            'description' => "Deleted region \"{$regionName}\"",
            'ip_address' => request()->ip(),
        ]);

        session()->flash('message', 'Region deleted successfully.');

        Flux::modal('delete-region-modal')->close();

        $this->closeModals();
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->resetForm();
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
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
        $this->selectedRegion = null;
        $this->cluster_id = null;
        $this->name = '';
        $this->description = '';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        $region = Region::create([
            'cluster_id' => $this->cluster_id,
            'name' => $this->name,
            'description' => $this->description,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'add_region',
            'description' => "Added region \"{$region->name}\"",
            'ip_address' => request()->ip(),
        ]);

        session()->flash('message', 'Region added successfully.');

        Flux::modal('add-region-modal')->close();

        $this->resetForm();
    }

    public function update()
    {
        $this->validate();

        $region = Region::findOrFail($this->selectedRegion);
        $region->update([
            'cluster_id' => $this->cluster_id,
            'name' => $this->name,
            'description' => $this->description,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'edit_region',
            'description' => "Edited region \"{$region->name}\"",
            'ip_address' => request()->ip(),
        ]);

        session()->flash('message', 'Region updated successfully.');

        Flux::modal('edit-region-modal')->close();

        $this->resetForm();
    }

    public function render()
    {
        \Log::info('RegionList render with search: ' . $this->search);
        $regions = Region::with('cluster')->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('cluster', function ($cq) {
                        $cq->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        \Log::info('RegionList query executed, count: ' . $regions->count());

        $clusters = Cluster::orderBy('name')->get();

        return view('livewire.admin.region-list', [
            'regions' => $regions,
            'clusters' => $clusters,
        ])->layout('components.layouts.admin');
    }
}
