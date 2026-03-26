<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Unit;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class EditEmployee extends Component
{

    // Employee fields
    public $employee_id;
    public $employee_number;
    public $first_name;
    public $last_name;
    public $middle_name;
    public $suffix;
    public $gender;
    public $birth_date;
    public $tin;
    public $landbank_account;
    public $contact_number;
    public $email;
    public $address;
    public $emergency_contact_name;
    public $emergency_contact_relationship;
    public $emergency_contact_number;
    public $office_id;
    public $unit_id;
    public $position_id;
    public $employment_status;
    public $role;

    // Options
    public $genderOptions = ['Male', 'Female'];
    public $relationshipOptions = ['Parent', 'Sibling', 'Spouse', 'Child', 'Friend', 'Other'];
    public $employmentStatusOptions = ['Hired', 'Resigned', 'Terminated'];
    public $officeOptions = [];
    public $unitOptions = [];
    public $positionOptions = [];
    public $roleOptions = [];


    public function mount($employeeId)
    {
        if (!Auth::user()->hasRole('administrator')) {
            abort(403, 'Unauthorized access');
        }

        $this->loadEmployeeData($employeeId);

        $this->officeOptions = Office::orderBy('name')->get();
        $this->positionOptions = Position::orderBy('name')->get();
        $this->roleOptions = Role::orderBy('name')->pluck('name')->toArray();
        $this->updateUnitOptions();
    }

    private function loadEmployeeData($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $this->employee_id = $employee->id;
        $this->employee_number = $employee->employee_number;
        $this->first_name = $employee->first_name;
        $this->last_name = $employee->last_name;
        $this->middle_name = $employee->middle_name;
        $this->suffix = $employee->suffix;
        $this->gender = $employee->gender;
        $this->birth_date = $employee->birth_date ? $employee->birth_date->format('Y-m-d') : null;
        $this->tin = $employee->tin;
        $this->landbank_account = $employee->landbank_account;
        $this->contact_number = $employee->contact_number;
        $this->email = $employee->email;
        $this->address = $employee->address;
        $this->emergency_contact_name = $employee->emergency_contact_name;
        $this->emergency_contact_relationship = $employee->emergency_contact_relationship;
        $this->emergency_contact_number = $employee->emergency_contact_number;
        $this->office_id = $employee->office_id;
        $this->unit_id = $employee->unit_id;
        $this->position_id = $employee->position_id;
        $this->employment_status = $employee->employment_status;

        // Load user role
        $user = User::where('employee_number', $employee->employee_number)->first();
        $this->role = $user ? $user->roles->pluck('name')->first() : '';
    }

    public function updatedOfficeId()
    {
        $this->unit_id = null;
        $this->updateUnitOptions();
    }

    private function updateUnitOptions()
    {
        if ($this->office_id) {
            $this->unitOptions = Unit::where('office_id', $this->office_id)->orderBy('name')->get();
        } else {
            $this->unitOptions = [];
        }
    }


    public function rules()
    {
        return [
            'employee_number' => 'required|integer|min:1|max:9999|unique:employees,employee_number,' . $this->employee_id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'birth_date' => 'required|date|before:today',
            'tin' => 'required|string|regex:/^\d{3}-\d{3}-\d{3}$/',
            'landbank_account' => 'required|string|max:255',
            'contact_number' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
            'email' => 'required|email|unique:employees,email,' . $this->employee_id,
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
            'office_id' => 'required|exists:offices,id',
            'unit_id' => 'nullable|exists:units,id',
            'position_id' => 'required|exists:positions,id',
            'employment_status' => 'required|string|in:Hired,Resigned,Terminated',
            'role' => 'required|string|in:' . implode(',', $this->roleOptions),
            ];
    }

    public function confirmSave()
    {
        LivewireAlert::title('Edit Employee')
            ->text('Are you sure you want to update this employee?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Update Employee')
            ->withCancelButton('Cancel')
            ->onConfirm('save')
            ->show();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'employee_number' => $this->employee_number,
            'first_name' => $this->properCase($this->first_name),
            'last_name' => $this->properCase($this->last_name),
            'middle_name' => $this->properCase($this->middle_name),
            'suffix' => $this->suffix,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'tin' => $this->tin,
            'landbank_account' => $this->landbank_account,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'address' => $this->address,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_relationship' => $this->emergency_contact_relationship,
            'emergency_contact_number' => $this->emergency_contact_number,
            'office_id' => $this->office_id,
            'unit_id' => $this->unit_id,
            'position_id' => $this->position_id,
            'employment_status' => $this->employment_status,
        ];

        $employee = Employee::findOrFail($this->employee_id);
        $employee->update($data);

        // Update user role
        $user = User::where('employee_number', $employee->employee_number)->first();
        if ($user) {
            $user->syncRoles([$this->role]);
        }

        // Log employee edit
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'edit_employee',
            'description' => "Edited employee {$employee->full_name} (Employee #{$employee->employee_number})",
            'ip_address' => request()->ip(),
            'affected_user_id' => $user ? $user->id : null,
            'affected_employee_id' => $employee->id,
        ]);

        session()->flash('message', 'Employee updated successfully.');

        return redirect()->route('admin.employee-list');
    }


    private function properCase($string)
    {
        return ucwords(strtolower($string));
    }

    public function render()
    {
        return view('livewire.admin.edit-employee')->layout('components.layouts.admin');
    }
}
