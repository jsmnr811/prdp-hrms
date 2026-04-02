<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class CreateRole extends Component
{
    public $roleName = '';
    public $selectedPermissions = [];

    public function mount()
    {
        if (!Auth::user()->hasRole('administrator')) {
            abort(403, 'Unauthorized access');
        }
    }

    public function confirmSaveRole()
    {
        LivewireAlert::title('Create Role')
            ->text('Are you sure you want to create this role with the selected permissions?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Create Role')
            ->withCancelButton('Cancel')
            ->onConfirm('saveRole')
            ->show();
    }

    public function saveRole()
    {
        try {
            $this->validate([
                'roleName' => 'required|string|max:255|unique:roles,name',
            ]);

            $role = Role::create(['name' => $this->roleName]);
            $role->permissions()->attach($this->selectedPermissions);
            LivewireAlert::success('Role created successfully!');

            return redirect()->route('admin.role-permission-management');
        } catch (\Exception $e) {
            LivewireAlert::error('Failed to create role: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $permissions = Permission::all();

        return view('livewire.admin.create-role', compact('permissions'))
            ->layout('components.layouts.admin');
    }
}
