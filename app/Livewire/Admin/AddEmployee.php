<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Unit;
use App\Models\Position;
use App\Models\OfficeCategory;
use App\Models\Cluster;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use App\Jobs\SendWelcomeEmailToUser;
use Illuminate\Support\Facades\Hash;

class AddEmployee extends Component
{

    // Employee fields
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
    public $office_category_id;
    public $cluster_id;
    public $region_id;
    public $office_id;
    public $unit_id;
    public $prefix;
    public $position_id;

    // Options
    public $genderOptions = ['Male', 'Female'];
    public $relationshipOptions = ['Parent', 'Sibling', 'Spouse', 'Child', 'Friend', 'Other'];
    public $officeCategoryOptions = [];
    public $clusterOptions = [];
    public $regionOptions = [];
    public $officeOptions = [];
    public $unitOptions = [];
    public $positionOptions = [];

    public function mount()
    {
        $user = Auth::user();

        if (! $user->can('create-employees')) {
            abort(403, 'Unauthorized access');
        }

        $employee = $user->employee ?? null;

        if ($employee) {
            // Office Categories
            $this->officeCategoryOptions = auth()->user()->hasRole('administrator')
                ? OfficeCategory::all()
                : OfficeCategory::where('id', $employee->office_category_id)->get();

            // Clusters
            $this->clusterOptions = Cluster::when(
                $employee->office_category_id >= 2,
                fn($query) => $query->where('id', $employee->cluster_id)
            )->get();

            // Regions
            $this->regionOptions = Region::when(
                $employee->office_category_id == 3,
                fn($query) => $query->where('id', $employee->region_id)
            )->get();
        } else {
            // fallback if no employee
            $this->officeCategoryOptions = OfficeCategory::get();
            $this->clusterOptions = Cluster::get();
            $this->regionOptions = Region::get();
        }

        if ($this->office_category_id != 1) {
            // Only show offices with IDs 3-6
            $this->officeOptions = Office::whereBetween('id', [3, 6])->get();
        } else {
            $this->officeOptions = Office::orderBy('name')->get();
        }

        if ($employee) {
            if ($employee->office_category_id == 2 && $employee->cluster) {
                $this->prefix = $employee->officeCategory->name . '-' . $employee->cluster->abbr;
            } elseif ($employee->office_category_id == 3 && $employee->region) {
                $this->prefix = $employee->officeCategory->name . '-' . $employee->region->abbr;
            } else {
                $this->prefix = '';
            }
        } else {
            $this->prefix = '';
        }

        $this->positionOptions = Position::orderBy('name')->get();
        $this->updateUnitOptions();
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

    public function updatedOfficeCategoryId()
    {
        $this->updatePrefix();
    }

    public function updatedClusterId()
    {
        $this->updatePrefix();
    }

    public function updatedRegionId()
    {
        $this->updatePrefix();
    }

    private function updatePrefix()
    {
        $this->prefix = '';

        if (!$this->office_category_id) {
            return;
        }

        $officeCategory = OfficeCategory::find($this->office_category_id);

        if (!$officeCategory) {
            return;
        }

        // PSO (Cluster-based)
        if ($this->office_category_id == 2 && $this->cluster_id) {
            $cluster = Cluster::find($this->cluster_id);

            if ($cluster) {
                $this->prefix = $officeCategory->name . '-' . $cluster->abbr;
            }
        }

        // RPCO (Region-based)
        elseif ($this->office_category_id == 3 && $this->region_id) {
            $region = Region::find($this->region_id);

            if ($region) {
                $this->prefix = $officeCategory->name . '-' . $region->abbr;
            }
        }
    }

    public function rules()
    {
        return [
            'employee_number' => [
                'required',
                function ($attribute, $value, $fail) {
                    $padded = str_pad($value, 4, '0', STR_PAD_LEFT);
                    $prefix = '';

                    if ($this->office_category_id) {
                        $officeCategory = OfficeCategory::find($this->office_category_id);
                        if ($officeCategory) {
                            if ($this->office_category_id == 2 && $this->cluster_id) {
                                $cluster = Cluster::find($this->cluster_id);
                                if ($cluster) {
                                    $prefix = $officeCategory->name . '-' . $cluster->abbr;
                                }
                            } elseif ($this->office_category_id == 3 && $this->region_id) {
                                $region = Region::find($this->region_id);
                                if ($region) {
                                    $prefix = $officeCategory->name . '-' . $region->abbr;
                                }
                            }
                        }
                    }

                    $fullEmployeeNumber = $prefix ? $prefix . '-' . $padded : $padded;

                    if (Employee::where('employee_number', $fullEmployeeNumber)->exists()) {
                        $fail('The employee number has already been taken.');
                    }
                },
            ],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'birth_date' => 'required|date|before:today',
            'tin' => 'required|string|regex:/^\d{3}-\d{3}-\d{3}$/',
            'landbank_account' => 'required|string|max:255',
            'contact_number' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
            'email' => 'required|email|unique:employees,email',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
            'office_category_id' => 'required|exists:office_categories,id',

            // Cluster required unless office_category_id = 1
            'cluster_id' => 'required_unless:office_category_id,1|exists:clusters,id',

            // Region required only if office_category_id = 3
            'region_id' => 'required_if:office_category_id,3|exists:regions,id',

            'office_id' => 'required|exists:offices,id',
            'unit_id' => 'nullable|exists:units,id',
            'position_id' => 'required|exists:positions,id',
        ];
    }

    public function confirmSave()
    {
        LivewireAlert::title('Add Employee')
            ->text('Are you sure you want to add this employee? A user account will be created automatically.')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Add Employee')
            ->withCancelButton('Cancel')
            ->onConfirm('save')
            ->show();
    }

    public function save()
    {

        $this->validate();

        // Build prefix
        $prefix = '';

        if ($this->office_category_id) {
            $officeCategory = OfficeCategory::find($this->office_category_id);

            if ($officeCategory) {
                if ($this->office_category_id == 2 && $this->cluster_id) {
                    $cluster = Cluster::find($this->cluster_id);
                    if ($cluster) {
                        $prefix = $officeCategory->name . '-' . $cluster->abbr; // e.g., PSO-NL
                    }
                } elseif ($this->office_category_id == 3 && $this->region_id) {
                    $region = Region::find($this->region_id);
                    if ($region) {
                        $prefix = $officeCategory->name . '-' . $region->abbr; // e.g., PSO-NL
                    }
                }
            }
        }

        // Combine prefix and employee number
        $employeeNumber = $prefix
            ? $prefix . '-' . $this->employee_number
            : $this->employee_number;

        // Prepare data
        $data = [
            'employee_number' => $employeeNumber,
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
            'office_category_id' => $this->office_category_id,
            'cluster_id' => $this->cluster_id,
            'region_id' => $this->region_id,
            'office_id' => $this->office_id,
            'unit_id' => $this->unit_id,
            'position_id' => $this->position_id,
            'employment_status' => 'Hired',
        ];

        // Save employee
        $employee = Employee::create($data);

        // Generate username
        $firstInitial = strtoupper(substr($employee->first_name, 0, 1));
        $lastInitial = strtolower(substr($employee->last_name, 0, 1));
        $username = $firstInitial . $lastInitial . $employee->employee_number;

        // Create user account
        $defaultPassword = $this->generateDefaultPassword($employee);

        $userData = [
            'username' => $username,
            'password' => Hash::make($defaultPassword),
            'employee_id' => $employee->id,
            'employee_number' => $employee->employee_number,
            'must_change_password' => false,
            'status' => 1,
            'email_verified_at' => now(),
        ];

        $user = User::create($userData);
        $user->assignRole('employee');

        // Log employee creation
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'add_employee',
            'description' => "Added employee {$employee->full_name} (Employee #{$employee->employee_number})",
            'ip_address' => request()->ip(),
            'affected_user_id' => $user->id,
            'affected_employee_id' => $employee->id,
        ]);

        // Send welcome email
        SendWelcomeEmailToUser::dispatch($user);

        session()->flash('message', 'Employee and user account created successfully.');

        return redirect()->route('admin.employee-list');
    }

    private function properCase($string)
    {
        return ucwords(strtolower($string));
    }

    private function generateDefaultPassword(Employee $employee)
    {
        $length = random_int(8, 9); // random length between 8 and 9
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $password;
    }

    public function render()
    {
        return view('livewire.admin.add-employee')->layout('components.layouts.admin');
    }
}
