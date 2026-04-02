<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class EditRole extends Component
{
    public $roleId;
    public $roleName = '';
    public $selectedPermissions = [];

    public function mount(Role $role)
    {
        if (!Auth::user()->hasRole('administrator')) {
            abort(403, 'Unauthorized access');
        }

        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
    }

    public function confirmSaveRole()
    {
        LivewireAlert::title('Update Role')
            ->text('Are you sure you want to update this role with the selected permissions?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Update Role')
            ->withCancelButton('Cancel')
            ->onConfirm('saveRole')
            ->show();
    }

    public function saveRole()
    {
        try {
            $this->validate([
                'roleName' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
            ]);

            $role = Role::find($this->roleId);
            $role->update(['name' => $this->roleName]);
            $role->permissions()->sync($this->selectedPermissions);
            LivewireAlert::success('Role updated successfully!');

            return redirect()->route('admin.role-permission-management');
        } catch (\Exception $e) {
            LivewireAlert::error('Failed to update role: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $permissions = Permission::all();

        return view('livewire.admin.edit-role', compact('permissions'))
            ->layout('components.layouts.admin');
    }
}
