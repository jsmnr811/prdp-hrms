<?php

namespace App\Livewire\Employee;

use App\Models\ActivityLog;
use App\Models\Cluster;
use App\Models\Office;
use App\Models\OfficeCategory;
use App\Models\Position;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfile extends Component
{
    use WithFileUploads;

    // I-Support office code constant
    public const I_SUPPORT_OFFICE_CODE = 'I-SUPPORT';

    // Tab state
    public $activeTab = 'personal';

    // Computed property for unit visibility
    public $showUnit = false;

    public $first_name;
    public $last_name;
    public $middle_name;
    public $suffix;
    public $contact_number;
    public $email;
    public $image;
    public $address;
    public $emergency_contact_name;
    public $emergency_contact_relationship;
    public $emergency_contact_number;
    public $gender;
    public $birth_date;
    public $tin;
    public $blood_type;
    public $landbank_account;
    public $height;
    public $weight;
    public $office_category_id;
    public $cluster_id;
    public $region_id;
    public $office_id;
    public $unit_id;
    public $position_id;
    public $officeCategoryOptions = [];
    public $clusterOptions = [];
    public $regionOptions = [];
    public $officeOptions = [];
    public $unitOptions = [];
    public $positionOptions = [];

    public $current_password;
    public $new_password;

    public $confirm_password;

    public $genderOptions = ['Male', 'Female'];
    public $bloodTypeOptions = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
    public $relationshipOptions = ['Parent', 'Sibling', 'Spouse', 'Child', 'Friend', 'Other'];

    public $employee;

    // Getter for employee to make it accessible in the view
    public function getEmployeeProperty()
    {
        return $this->employee;
    }

    public function mount()
    {
        if (! Auth::user()->hasRole('employee')) {
            abort(403, 'Unauthorized access');
        }

        $this->employee = Auth::user()->employee;

        // Personal information
        $this->first_name = $this->employee->first_name;
        $this->last_name = $this->employee->last_name;
        $this->middle_name = $this->employee->middle_name;
        $this->suffix = $this->employee->suffix;
        $this->gender = $this->employee->gender;
        $this->birth_date = $this->employee->birth_date ? $this->employee->birth_date->format('Y-m-d') : null;
        $this->tin = $this->employee->tin;
        $this->blood_type = $this->employee->blood_type;
        $this->landbank_account = $this->employee->landbank_account;
        $this->height = $this->employee->height;
        $this->weight = $this->employee->weight;

        // Contact information
        $this->email = $this->employee->email;
        $this->contact_number = $this->employee->contact_number;
        $this->address = $this->employee->address;
        $this->emergency_contact_name = $this->employee->emergency_contact_name;
        $this->emergency_contact_relationship = $this->employee->emergency_contact_relationship;
        $this->emergency_contact_number = $this->employee->emergency_contact_number;

        // Organizational information
        $this->office_category_id = $this->employee->office_category_id;
        $this->cluster_id = $this->employee->cluster_id;
        $this->region_id = $this->employee->region_id;
        $this->office_id = $this->employee->office_id;
        $this->unit_id = $this->employee->unit_id;
        $this->position_id = $this->employee->position_id;

        // ✅ Load options
        $this->officeCategoryOptions = OfficeCategory::pluck('name', 'id')->toArray();
        $this->clusterOptions = Cluster::pluck('name', 'id')->toArray();
        $this->regionOptions = Region::pluck('name', 'id')->toArray();
        $this->officeOptions = Office::pluck('name', 'id')->toArray();

        // ✅ Load units - only for I-SUPPORT office
        if ($this->employee->office && $this->employee->office->code === self::I_SUPPORT_OFFICE_CODE) {
            $this->showUnit = true;
            $this->unitOptions = $this->employee->office->units->pluck('name', 'id')->toArray();
        }

        // Positions (if not dependent)
        $this->positionOptions = Position::pluck('name', 'id')->toArray();
    }

    /**
     * Store uploaded image with random filename in employee-specific directory
     */
    private function storeImage()
    {
        if (!$this->image) {
            return null;
        }

        // Delete the old image if it exists
        if ($this->employee->image) {
            Storage::disk('public')->delete($this->employee->image);
        }

        $filename = Str::random(40) . '.' . $this->image->getClientOriginalExtension();

        $directory = 'employee_images/' . $this->employee->employee_number;
        Storage::disk('public')->makeDirectory($directory);

        $path = $directory . '/' . $filename;
        Storage::disk('public')->put($path, file_get_contents($this->image->getRealPath()));

        return $path;
    }

    private function properCase($value)
    {
        if (!$value) return $value;

        return collect(explode(' ', strtolower($value)))
            ->map(function ($word) {
                return ucfirst($word);
            })
            ->implode(' ');
    }

    /**
     * Confirm Personal Information Update
     */
    public function confirmPersonal()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'birth_date' => 'required|date',
            'tin' => 'required|string|regex:/^\d{3}-\d{3}-\d{3}$/',
            'blood_type' => 'required|string|max:10',
            'landbank_account' => 'required|string|max:255',
            'height' => 'required|numeric|min:0|max:300',
            'weight' => 'required|numeric|min:0|max:500',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,jfif|max:5120',
        ]);

        LivewireAlert::title('Confirm Personal Information Update')
            ->text('Are you sure you want to update your personal information?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Update')
            ->withCancelButton('Cancel')
            ->onConfirm('confirmedPersonal')
            ->show();
    }

    /**
     * Confirmed Personal Information Update
     */
    public function confirmedPersonal()
    {
        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'suffix' => $this->suffix,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'tin' => $this->tin,
            'blood_type' => $this->blood_type,
            'landbank_account' => $this->landbank_account,
            'height' => $this->height,
            'weight' => $this->weight,
        ];

        $data['image'] = $this->storeImage();

        $this->employee->update($data);

        session()->flash('personal_message', 'Personal information updated successfully.');
    }

    /**
     * Save Personal Information
     */
    public function updatePersonal()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'birth_date' => 'required|date',
            'tin' => 'required|string|regex:/^\d{3}-\d{3}-\d{3}$/',
            'blood_type' => 'required|string|max:10',
            'landbank_account' => 'required|string|max:255',
            'height' => 'required|numeric|min:0|max:300',
            'weight' => 'required|numeric|min:0|max:500',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,jfif|max:5120',
        ]);

        $data = [
            'first_name' => $this->properCase($this->first_name),
            'last_name' => $this->properCase($this->last_name),
            'middle_name' => $this->properCase($this->middle_name),
            'suffix' => $this->suffix,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'tin' => $this->tin,
            'blood_type' => $this->blood_type,
            'landbank_account' => $this->landbank_account,
            'height' => $this->height,
            'weight' => $this->weight,
        ];

        $data['image'] = $this->storeImage();

        $this->employee->update($data);

        // Log profile update
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_profile',
            'description' => 'Updated personal information',
            'ip_address' => request()->ip(),
        ]);

        session()->flash('personal_message', 'Personal information updated successfully.');
    }

    /**
     * Confirm Contact Information Update
     */
    public function confirmContact()
    {
        $this->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('employees', 'email')->ignore($this->employee->id),
            ],
            'contact_number' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
        ]);

        LivewireAlert::title('Confirm Contact Information Update')
            ->text('Are you sure you want to update your contact information?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Update')
            ->withCancelButton('Cancel')
            ->onConfirm('confirmedContact')
            ->show();
    }

    /**
     * Confirmed Contact Information Update
     */
    public function confirmedContact()
    {
        $data = [
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_relationship' => $this->emergency_contact_relationship,
            'emergency_contact_number' => $this->emergency_contact_number,
        ];

        $this->employee->update($data);

        // Log profile update
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_profile',
            'description' => 'Updated contact information',
            'ip_address' => request()->ip(),
        ]);

        session()->flash('contact_message', 'Contact information updated successfully.');
    }

    /**
     * Save Contact Information
     */
    public function updateContact()
    {
        $this->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('employees', 'email')->ignore($this->employee->id),
            ],
            'contact_number' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
        ]);

        $data = [
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'emergency_contact_name' => $this->properCase($this->emergency_contact_name),
            'emergency_contact_relationship' => $this->emergency_contact_relationship,
            'emergency_contact_number' => $this->emergency_contact_number,
        ];

        $this->employee->update($data);

        session()->flash('contact_message', 'Contact information updated successfully.');
    }

    /**
     * Confirm Organizational Information Update
     */
    public function confirmOrganizational()
    {
        $this->validate([
            'office_category_id' => 'nullable|exists:office_categories,id',
            'cluster_id' => 'nullable|exists:clusters,id',
            'region_id' => 'nullable|exists:regions,id',
            'office_id' => 'required|exists:offices,id',
            'position_id' => 'required|exists:positions,id',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        LivewireAlert::title('Confirm Organizational Information Update')
            ->text('Are you sure you want to update your organizational information?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Update')
            ->withCancelButton('Cancel')
            ->onConfirm('confirmedOrganizational')
            ->show();
    }

    /**
     * Confirmed Organizational Information Update
     */
    public function confirmedOrganizational()
    {
        $data = [
            'office_category_id' => $this->office_category_id,
            'cluster_id' => $this->cluster_id,
            'region_id' => $this->region_id,
            'office_id' => $this->office_id,
            'position_id' => $this->position_id,
            'unit_id' => $this->showUnit ? $this->unit_id : null,
        ];

        $this->employee->update($data);

        // Log profile update
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_profile',
            'description' => 'Updated organizational information',
            'ip_address' => request()->ip(),
        ]);

        session()->flash('organizational_message', 'Organizational information updated successfully.');
    }

    /**
     * Save Organizational Information
     */
    public function updateOrganizational()
    {
        $this->validate([
            'office_category_id' => 'nullable|exists:office_categories,id',
            'cluster_id' => 'nullable|exists:clusters,id',
            'region_id' => 'nullable|exists:regions,id',
            'office_id' => 'required|exists:offices,id',
            'position_id' => 'required|exists:positions,id',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        $data = [
            'office_category_id' => $this->office_category_id,
            'cluster_id' => $this->cluster_id,
            'region_id' => $this->region_id,
            'office_id' => $this->office_id,
            'position_id' => $this->position_id,
            'unit_id' => $this->showUnit ? $this->unit_id : null,
        ];

        $this->employee->update($data);

        session()->flash('organizational_message', 'Organizational information updated successfully.');
    }

    /**
     * Legacy method - saves all profile data
     * @deprecated Use updatePersonal(), updateContact(), or updateOrganizational() instead
     */
    public function updateProfile()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'contact_number' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
            'email' => [
                'required',
                'email',
                Rule::unique('employees')->ignore($this->employee->id),
            ],
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,jfif|max:5120',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|regex:/^[0-9\-\+\(\)\s]+$/|max:20',
            'gender' => 'nullable|string|in:Male,Female',
            'birth_date' => 'nullable|date',
            'tin' => 'nullable|string|regex:/^\d{3}-\d{3}-\d{3}$/',
            'blood_type' => 'nullable|string|max:10',
            'landbank_account' => 'nullable|string|max:255',
            'height' => 'nullable|numeric|min:0|max:300',
            'weight' => 'nullable|numeric|min:0|max:500',
        ]);

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'suffix' => $this->suffix,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'address' => $this->address,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_relationship' => $this->emergency_contact_relationship,
            'emergency_contact_number' => $this->emergency_contact_number,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'tin' => $this->tin,
            'blood_type' => $this->blood_type,
            'landbank_account' => $this->landbank_account,
            'height' => $this->height,
            'weight' => $this->weight,
            'office_id' => $this->office_id,
            'unit_id' => $this->unit_id,
            'position_id' => $this->position_id,
        ];

        if ($this->image) {
            $data['image'] = $this->storeImage();
        }

        $this->employee->update($data);

        session()->flash('message', 'Profile updated successfully.');
    }

    /**
     * Livewire lifecycle hook - called when office_id is updated via wire:model
     * This ONLY handles show/hide behavior for the Unit field - NO data saving
     */
    public function updatedOfficeId()
    {
        // Reset unit selection when office changes (show/hide behavior only - NOT saving)
        $this->unit_id = null;
        $this->unitOptions = [];
        $this->showUnit = false;

        if (!$this->office_id) {
            return;
        }

        // Check if selected office is I-SUPPORT - ONLY show unit for I-SUPPORT
        $office = Office::find($this->office_id);
        if ($office && $office->code === self::I_SUPPORT_OFFICE_CODE) {
            // I-SUPPORT office - SHOW unit field and load units (show/hide behavior only - NOT saving)
            $this->showUnit = true;
            $this->unitOptions = $office->units->pluck('name', 'id')->toArray();
        }
        // For other offices, keep showUnit false and unitOptions empty
    }

    /**
     * Legacy method kept for backward compatibility
     * @deprecated Use updatedOfficeId() instead
     */
    public function onOfficeChange()
    {
        // Delegate to updatedOfficeId for consistency
        $this->updatedOfficeId();
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password matches
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }

        // Update password
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        // Log password change
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'change_password',
            'description' => 'Changed password',
            'ip_address' => request()->ip(),
        ]);

        // Clear fields
        $this->reset(['current_password', 'new_password', 'confirm_password']);

        // Flash success message
        session()->flash('password_message', 'Password changed successfully.');
    }

    public function render()
    {
        return view('livewire.employee.update-profile')->layout('components.layouts.app');
    }
}
