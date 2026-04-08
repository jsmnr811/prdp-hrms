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
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class EditEmployee extends Component
{
    public $employee_id;
    public $original_employee_id;
    public $employee_number;
    public $originalEmployeeNumber;
    public $originalData = [];

    public $first_name, $last_name, $middle_name, $suffix;
    public $gender, $birth_date, $tin, $landbank_account;
    public $contact_number, $email, $address;

    public $emergency_contact_name, $emergency_contact_relationship, $emergency_contact_number;

    public $office_category_id, $cluster_id, $region_id;
    public $office_id, $unit_id, $position_id;

    public $employment_status, $role;

    public $prefix;

    // Options
    public $genderOptions = ['Male', 'Female'];
    public $relationshipOptions = ['Parent', 'Sibling', 'Spouse', 'Child', 'Friend', 'Other'];
    public $employmentStatusOptions = ['Hired', 'Resigned', 'Terminated'];

    public $officeCategoryOptions = [], $clusterOptions = [], $regionOptions = [];
    public $officeOptions = [], $unitOptions = [], $positionOptions = [], $roleOptions = [];

    public function mount($employeeId)
    {
        
        $user = Auth::user();
         if (! $user->can('edit-employees')) {
            abort(403, 'Unauthorized access');
        }
        $employee = Employee::findOrFail($employeeId);
        $this->original_employee_id = $employee->id;
        $this->employee_id = $employee->id;
        $this->employee_number = intval(substr($employee->employee_number, -4)); // numeric part
        $this->originalEmployeeNumber = $employee->employee_number;


        abort_unless($user->can('edit-employees'), 403);

        $this->loadEmployeeData($employeeId);

        $this->initializeOptions($user);
        $this->setPrefixFromSelection();
        $this->updateUnitOptions();
    }

    private function initializeOptions($user)
    {
        $employee = $user->employee ?? null;

        if ($employee) {
            $this->officeCategoryOptions = OfficeCategory::where('id', $employee->office_category_id)->get();

            $this->clusterOptions = Cluster::when(
                $employee->office_category_id >= 2,
                fn($q) => $q->where('id', $employee->cluster_id)
            )->get();

            $this->regionOptions = Region::when(
                $employee->office_category_id == 3,
                fn($q) => $q->where('id', $employee->region_id)
            )->get();
        } else {
            $this->officeCategoryOptions = OfficeCategory::get();
            $this->clusterOptions = Cluster::get();
            $this->regionOptions = Region::get();
        }

        $this->officeOptions = $this->office_category_id != 1
            ? Office::whereBetween('id', [3, 6])->get()
            : Office::orderBy('name')->get();

        $this->positionOptions = Position::orderBy('name')->get();
        $this->roleOptions = Role::pluck('name')->toArray();
    }

    private function loadEmployeeData($id)
    {
        $e = Employee::findOrFail($id);

        $this->employee_id = $e->id;

        // ✅ REMOVE PREFIX
        $this->employee_number = $this->extractNumber($e->employee_number);

        $this->first_name = $e->first_name;
        $this->last_name = $e->last_name;
        $this->middle_name = $e->middle_name;
        $this->suffix = $e->suffix;
        $this->gender = $e->gender;
        $this->birth_date = optional($e->birth_date)->format('Y-m-d');
        $this->tin = $e->tin;
        $this->landbank_account = $e->landbank_account;
        $this->contact_number = $e->contact_number;
        $this->email = $e->email;
        $this->address = $e->address;

        $this->emergency_contact_name = $e->emergency_contact_name;
        $this->emergency_contact_relationship = $e->emergency_contact_relationship;
        $this->emergency_contact_number = $e->emergency_contact_number;

        $this->office_category_id = $e->office_category_id;
        $this->cluster_id = $e->cluster_id;
        $this->region_id = $e->region_id;
        $this->office_id = $e->office_id;
        $this->unit_id = $e->unit_id;
        $this->position_id = $e->position_id;
        $this->employment_status = $e->employment_status;

        // Store original data for change tracking
        $this->originalData = $e->toArray();

        // Set prefix based on loaded data
        $this->setPrefixFromSelection();

        $user = User::where('employee_id', $e->id)->first();
        $this->role = optional($user?->roles->first())->name;
    }

    private function extractNumber($value)
    {
        return $value ? last(explode('-', $value)) : null;
    }

    private function buildPrefix()
    {
        if (!$this->office_category_id) return '';

        $officeCategory = OfficeCategory::find($this->office_category_id);

        if (!$officeCategory) return '';

        if ($this->office_category_id == 2 && $this->cluster_id) {
            $cluster = Cluster::find($this->cluster_id);
            return $cluster ? $officeCategory->name . '-' . $cluster->abbr : '';
        }

        if ($this->office_category_id == 3 && $this->region_id) {
            $region = Region::find($this->region_id);
            return $region ? $officeCategory->name . '-' . $region->abbr : '';
        }

        return '';
    }

    private function setPrefixFromSelection()
    {
        $this->prefix = $this->buildPrefix();
    }

    public function updatedOfficeCategoryId()
    {
        $this->setPrefixFromSelection();
    }
    public function updatedClusterId()
    {
        $this->setPrefixFromSelection();
    }
    public function updatedRegionId()
    {
        $this->setPrefixFromSelection();
    }

    public function updatedOfficeId()
    {
        $this->unit_id = null;
        $this->updateUnitOptions();
    }

    private function updateUnitOptions()
    {
        $this->unitOptions = $this->office_id
            ? Unit::where('office_id', $this->office_id)->get()
            : [];
    }
    private function extractPrefix($fullNumber)
    {
        if (!$fullNumber) return '';
        $parts = explode('-', $fullNumber);
        array_pop($parts); // remove numeric part
        return implode('-', $parts); // join remaining prefix
    }
    public function rules()
    {
        return [
            'employee_number' => [
                'required',
                function ($attribute, $value, $fail) {
                    $padded = str_pad($value, 4, '0', STR_PAD_LEFT);
                    $prefix = $this->buildPrefix();
                    $fullEmployeeNumber = $prefix ? $prefix . '-' . $padded : $padded;

                    // Check if the built number is already taken by another employee
                    $existingEmployee = Employee::where('employee_number', $fullEmployeeNumber)->first();

                    if ($existingEmployee) {
                        // If it's taken by an employee that has a different original number, it's a conflict
                        if ($existingEmployee->employee_number !== $this->originalEmployeeNumber) {
                            $fail('The employee number has already been taken.');
                        }
                        // If it's the same employee (same number), allow it
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
            'email' => 'required|email|unique:employees,email,' . $this->employee_id,
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
            'office_category_id' => 'required|exists:office_categories,id',
            'cluster_id' => 'required_unless:office_category_id,1|exists:clusters,id',
            'region_id' => 'required_if:office_category_id,3|exists:regions,id',
            'office_id' => 'required|exists:offices,id',
            'unit_id' => 'nullable|exists:units,id',
            'position_id' => 'required|exists:positions,id',
            'employment_status' => 'required|string|in:Hired,Resigned,Terminated',
            'role' => 'required|string',
        ];
    }

    public function confirmSave()
    {
        LivewireAlert::title('Edit Employee')
            ->text('Are you sure you want to edit this employee?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Edit Employee')
            ->withCancelButton('Cancel')
            ->onConfirm('save')
            ->show();
    }

    public function save()
    {
        $this->validate();

        $employeeNumber = $this->buildPrefix()
            ? $this->buildPrefix() . '-' . str_pad($this->employee_number, 4, '0', STR_PAD_LEFT)
            : str_pad($this->employee_number, 4, '0', STR_PAD_LEFT);

        $employee = Employee::findOrFail($this->employee_id);

        // Prepare new data
        $newData = [
            'employee_number' => $employeeNumber,
            'first_name' => ucwords(strtolower($this->first_name)),
            'last_name' => ucwords(strtolower($this->last_name)),
            'middle_name' => ucwords(strtolower($this->middle_name)),
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
            'employment_status' => $this->employment_status,
        ];

        $employee->update($newData);

        $user = User::where('employee_id', $employee->id)->first();
        if ($user) {
            $user->update([
                'employee_number' => $employeeNumber,
                'status' => $this->employment_status === 'Hired' ? 1 : 0,
            ]);

            // Sync user role
            $user->syncRoles([$this->role]);
        }

        // Log employee edit with changes
        $changes = $this->getChangesDescription($this->originalData, $newData);
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'edit_employee',
            'description' => "Edited employee {$employee->full_name} (Employee #{$employee->employee_number}). Changes: {$changes}",
            'ip_address' => request()->ip(),
            'affected_user_id' => $user ? $user->id : null,
            'affected_employee_id' => $employee->id,
        ]);

        return redirect()->route('admin.employee-list');
    }

    private function getChangesDescription($original, $new)
    {
        $changes = [];
        $fieldLabels = [
            'employee_number' => 'Employee Number',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'middle_name' => 'Middle Name',
            'suffix' => 'Suffix',
            'gender' => 'Gender',
            'birth_date' => 'Birth Date',
            'tin' => 'TIN',
            'landbank_account' => 'Landbank Account',
            'contact_number' => 'Contact Number',
            'email' => 'Email',
            'address' => 'Address',
            'emergency_contact_name' => 'Emergency Contact Name',
            'emergency_contact_relationship' => 'Emergency Contact Relationship',
            'emergency_contact_number' => 'Emergency Contact Number',
            'office_category_id' => 'Office Category',
            'cluster_id' => 'Cluster',
            'region_id' => 'Region',
            'office_id' => 'Office',
            'unit_id' => 'Unit',
            'position_id' => 'Position',
            'employment_status' => 'Employment Status',
        ];

        foreach ($fieldLabels as $field => $label) {
            if (isset($original[$field]) && isset($new[$field])) {
                $oldValue = $original[$field];
                $newValue = $new[$field];

                // Handle date fields
                if (in_array($field, ['birth_date'])) {
                    $oldValue = $oldValue ? date('Y-m-d', strtotime($oldValue)) : null;
                    $newValue = $newValue ? date('Y-m-d', strtotime($newValue)) : null;
                }

                // Handle ID fields - get names instead of IDs
                if ($field === 'office_category_id' && $newValue) {
                    $oldValue = $oldValue ? OfficeCategory::find($oldValue)?->name : null;
                    $newValue = OfficeCategory::find($newValue)?->name;
                } elseif ($field === 'cluster_id' && $newValue) {
                    $oldValue = $oldValue ? Cluster::find($oldValue)?->name : null;
                    $newValue = Cluster::find($newValue)?->name;
                } elseif ($field === 'region_id' && $newValue) {
                    $oldValue = $oldValue ? Region::find($oldValue)?->name : null;
                    $newValue = Region::find($newValue)?->name;
                } elseif ($field === 'office_id' && $newValue) {
                    $oldValue = $oldValue ? Office::find($oldValue)?->name : null;
                    $newValue = Office::find($newValue)?->name;
                } elseif ($field === 'unit_id' && $newValue) {
                    $oldValue = $oldValue ? Unit::find($oldValue)?->name : null;
                    $newValue = Unit::find($newValue)?->name;
                } elseif ($field === 'position_id' && $newValue) {
                    $oldValue = $oldValue ? Position::find($oldValue)?->name : null;
                    $newValue = Position::find($newValue)?->name;
                }

                if ($oldValue != $newValue) {
                    $changes[] = "{$label}: '{$oldValue}' → '{$newValue}'";
                }
            }
        }

        return empty($changes) ? 'No changes detected' : implode(', ', $changes);
    }

    public function render()
    {
        return view('livewire.admin.edit-employee')->layout('components.layouts.admin');
    }
}
