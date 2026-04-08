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
        if (!Auth::user()->can('view-employees')) {
            abort(403, 'Unauthorized access');
        }
    }

    // Reset pagination on filters
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

    // ==============================
    // ACTIONS
    // ==============================

    public function confirmResendWelcomeEmail($employeeId)
    {
        $employee = Employee::where('employee_number', $employeeId)->first();

        if (!$employee) {
            session()->flash('error', 'Employee not found.');
            return;
        }

        $name = $employee->full_name;

        LivewireAlert::title('Send Welcome Emails')
            ->text("Resend welcome email to \"{$name}\"?")
            ->question()
            ->withConfirmButton('Yes')
            ->withCancelButton('Cancel')
            ->onConfirm('resendWelcomeEmail', ['employeeId' => $employeeId])
            ->show();
    }

    public function resendWelcomeEmail($employeeId)
    {
        $user = User::where('employee_number', $employeeId)->first();

        if (!$user || !$user->employee) {
            session()->flash('error', 'User not found.');
            return;
        }

        $employee = $user->employee;

        $defaultPassword =
            strtoupper(substr($employee->first_name, 0, 1)) .
            trim($employee->last_name) .
            $employee->employee_number;

        $user->update([
            'password' => Hash::make($defaultPassword)
        ]);

        Mail::to($user->email)
            ->send(new \App\Mail\WelcomeEmail($user, $defaultPassword));

        session()->flash('message', 'Welcome email resent.');
    }

    public function confirmResetPassword($employeeId)
    {
        $employee = Employee::where('employee_number', $employeeId)->first();

        if (!$employee || !$employee->user) {
            session()->flash('error', 'Employee not found.');
            return;
        }

        LivewireAlert::title('Reset Password')
            ->text("Reset password for \"{$employee->full_name}\"?")
            ->question()
            ->withConfirmButton('Yes')
            ->withCancelButton('Cancel')
            ->onConfirm('resetPassword', ['employeeId' => $employeeId])
            ->show();
    }

    public function resetPassword($employeeId)
    {
        $employee = Employee::where('employee_number', $employeeId)->first();

        if (!$employee || !$employee->user) {
            session()->flash('error', 'Employee not found.');
            return;
        }

        $defaultPassword =
            strtoupper(substr($employee->first_name, 0, 1)) .
            strtolower(preg_replace('/\s+/', '', $employee->last_name)) .
            $employee->employee_number;

        $employee->user->update([
            'password' => Hash::make($defaultPassword)
        ]);

        Mail::to($employee->user->email)
            ->send(new \App\Mail\TemporaryPassword(
                $employee->user,
                $defaultPassword,
                $employee->employee_number
            ));

        session()->flash('message', 'Password reset successful.');
    }

    public function editEmployee($employeeId)
    {
        return redirect()->route('admin.edit-employee', $employeeId);
    }

    // ==============================
    // RENDER
    // ==============================

    public function render()
    {
        $user = Auth::user();

        $employees = Employee::with(['user', 'office', 'unit', 'position'])
            ->visibleTo($user) // 🔥 MAIN FILTER
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$this->search}%"])
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('employee_number', 'like', "%{$this->search}%");
                });
            })
            ->when(
                $this->statusFilter,
                fn($q) =>
                $q->where('employment_status', $this->statusFilter)
            )
            ->when(
                $this->officeFilter,
                fn($q) =>
                $q->where('office_id', $this->officeFilter)
            )
            ->when(
                $this->unitFilter,
                fn($q) =>
                $q->where('unit_id', $this->unitFilter)
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        // 🔥 FILTERED DROPDOWNS
        $offices = Office::query()
            ->when(!$user->hasRole('administrator') && $user->employee, function ($q) use ($user) {
                $emp = $user->employee;

                // Filter offices THROUGH employees (correct way)
                $q->whereHas('employees', function ($query) use ($emp) {
                    $query->where('office_category_id', $emp->office_category_id)
                        ->when($emp->office_category_id >= 2, function ($q) use ($emp) {
                            $q->where('cluster_id', $emp->cluster_id);
                        })
                        ->when($emp->office_category_id == 3, function ($q) use ($emp) {
                            $q->where('region_id', $emp->region_id);
                        });
                });
            })
            ->orderBy('name')
            ->get();

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
