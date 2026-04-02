<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class EditPermission extends Component
{
    public $permissionId;
    public $permissionName = '';

    public function mount(Permission $permission)
    {
        if (!Auth::user()->hasRole('administrator')) {
            abort(403, 'Unauthorized access');
        }

        $this->permissionId = $permission->id;
        $this->permissionName = $permission->name;
    }

    public function confirmSavePermission()
    {
        LivewireAlert::title('Update Permission')
            ->text('Are you sure you want to update this permission?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Update Permission')
            ->withCancelButton('Cancel')
            ->onConfirm('savePermission')
            ->show();
    }

    public function savePermission()
    {
        try {
            $this->validate([
                'permissionName' => 'required|string|max:255|unique:permissions,name,' . $this->permissionId,
            ]);

            $permission = Permission::find($this->permissionId);
            $permission->update(['name' => $this->permissionName]);
            LivewireAlert::success('Permission updated successfully!');

            return redirect()->route('admin.role-permission-management');
        } catch (\Exception $e) {
            LivewireAlert::error('Failed to update permission: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.edit-permission')
            ->layout('components.layouts.admin');
    }
}
