<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use App\Models\Office;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class EmployeeList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $officeFilter = '';
    public $unitFilter = '';
    public $sortField = 'employee_number';
    public $sortDirection = 'asc';
    public $showResetPasswordModal = false;
    public $selectedEmployeeId;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'officeFilter' => ['except' => ''],
        'unitFilter' => ['except' => ''],
        'sortField' => ['except' => 'employee_number'],
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

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingOfficeFilter()
    {
        $this->resetPage();
    }

    public function updatingUnitFilter()
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
    public function confirmResendWelcomeEmail($employeeId)
    {
        $employee = Employee::where('employee_number', $employeeId)->first();
        if (!$employee) {
            session()->flash('error', 'Employee not found.');
            return;
        }

        $employeeName = $employee->first_name . ' ' . $employee->last_name;

        LivewireAlert::title('Send Welcome Emails')
            ->text("Are you sure you want to resend welcome emails to \"{$employeeName}\"?")
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Send Emails')
            ->withCancelButton('Cancel')
            // pass the ID as a parameter to the callback
            ->onConfirm('resendWelcomeEmail', ['employeeId' => $employeeId])
            ->show();
    }
    public function resendWelcomeEmail($employeeId)
    {
        $user = User::where('employee_number', $employeeId)->first();
        if (!$user || !$user->employee) {
            session()->flash('error', 'User not found or has no employee record.');
            return;
        }

        $employee = $user->employee;

        // Generate default password: first initial + last name + employee number
        $firstInitial = strtoupper(substr($employee->first_name, 0, 1));
        $lastName = trim($employee->last_name);
        $employeeNumber = $employee->employee_number;
        $defaultPassword = $firstInitial . $lastName . $employeeNumber;

        // Hash and save
        $user->password = Hash::make($defaultPassword);
        $user->save();

        // Send welcome email with new credentials
        Mail::to($user->email)->send(new \App\Mail\WelcomeEmail($user, $defaultPassword));

        session()->flash('message', 'Password reset to default and welcome email sent to ' . $user->name);
    }

    public function confirmResetPassword($employeeId)
    {
        $employee = Employee::where('employee_number', $employeeId)->first();

        if (!$employee || !$employee->user) {
            session()->flash('error', 'Employee or user not found.');
            return;
        }

        $employeeName = $employee->first_name . ' ' . $employee->last_name;

        LivewireAlert::title('Reset Password')
            ->text("Are you sure you want to reset the password for \"{$employeeName}\" to the default?")
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Reset Password')
            ->withCancelButton('Cancel')
            ->onConfirm('resetPassword', ['employeeId' => $employeeId])
            ->show();
    }

    public function resetPassword($employeeId)
    {
        $employee = Employee::where('employee_number', $employeeId)->first();
        if (!$employee || !$employee->user) {
            session()->flash('error', 'Employee or user not found.');
            return;
        }

        // Generate default password: first initial + last name + employee number
        $firstInitial = strtoupper(substr($employee->first_name, 0, 1));
        $lastName = preg_replace('/\s+/', '', strtolower($employee->last_name));
        $employeeNumber = $employee->employee_number;
        $defaultPassword = $firstInitial . $lastName . $employeeNumber;

        // Hash and save
        $employee->user->password = Hash::make($defaultPassword);
        $employee->user->save();

        // Send notification email
        Mail::to($employee->user->email)->send(new \App\Mail\TemporaryPassword($employee->user, $defaultPassword, $employeeNumber));

        session()->flash('message', 'Password reset to default and notification email sent to ' . $employee->user->name);
    }

    public function editEmployee($employeeId)
    {
        return redirect()->route('admin.edit-employee', $employeeId);
    }

    public function render()
    {
        $employees = Employee::with(['user', 'office', 'unit', 'position'])
            // ->whereHas('user', function ($query) {
            //     $query->role('employee');
            // })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereRaw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) LIKE ?", ['%' . $this->search . '%'])
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('employee_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('employment_status', $this->statusFilter);
            })
            ->when($this->officeFilter, function ($query) {
                $query->where('office_id', $this->officeFilter);
            })
            ->when($this->unitFilter, function ($query) {
                $query->where('unit_id', $this->unitFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        // Logging removed after verification

        $offices = Office::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $statuses = ['Hired', 'Resigned', 'Terminated'];

        return view('livewire.admin.employee-list', [
            'employees' => $employees,
            'offices' => $offices,
            'units' => $units,
            'statuses' => $statuses,
        ])->layout('components.layouts.admin');
    }
}
