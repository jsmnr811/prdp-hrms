<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class CreatePermission extends Component
{
    public $permissionName = '';

    public function mount()
    {
        if (!Auth::user()->hasRole('administrator')) {
            abort(403, 'Unauthorized access');
        }
    }

    public function confirmSavePermission()
    {
        LivewireAlert::title('Create Permission')
            ->text('Are you sure you want to create this permission?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Create Permission')
            ->withCancelButton('Cancel')
            ->onConfirm('savePermission')
            ->show();
    }

    public function savePermission()
    {
        try {
            $this->validate([
                'permissionName' => 'required|string|max:255|unique:permissions,name',
            ]);

            Permission::create(['name' => $this->permissionName]);
            LivewireAlert::success('Permission created successfully!');

            return redirect()->route('admin.role-permission-management');
        } catch (\Exception $e) {
            LivewireAlert::error('Failed to create permission: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.create-permission')
            ->layout('components.layouts.admin');
    }
}
