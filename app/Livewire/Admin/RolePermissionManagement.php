<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class RolePermissionManagement extends Component
{
    public $roles = [];
    public $permissions = [];

    public $activeTab = 'roles';

    protected $listeners = ['refreshRoles' => '$refresh'];

    public function mount()
    {
        if (!Auth::user()->hasRole('administrator')) {
            abort(403, 'Unauthorized access');
        }

        $this->loadData();
    }

    public function loadData()
    {
        $this->roles = Role::with('permissions')->withCount('users')->get();
        $this->permissions = Permission::withCount('roles')->get();
    }

    // Role CRUD
    public function deleteRole($roleId)
    {
        $role = Role::find($roleId);
        if ($role && $role->name !== 'administrator') {
            $role->delete();
            LivewireAlert::success('Role deleted successfully!');
            $this->loadData();
        } else {
            LivewireAlert::error('Cannot delete administrator role!');
        }
    }

    // Permission CRUD
    public function deletePermission($permissionId)
    {
        $permission = Permission::find($permissionId);
        if ($permission) {
            $permission->delete();
            LivewireAlert::success('Permission deleted successfully!');
            $this->loadData();
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.role-permission-management')
            ->layout('components.layouts.admin');
    }
}
