<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Office;
use App\Models\Position;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

use App\Mail\RegistrationPassword;

class Register extends Component
{
    use WithFileUploads;

    // Personal Information
    public $employee_number;
    public $office_category_id = '';
    public $cluster_id = '';
    public $region_id = '';
    public $office_id = '';
    public $unit_id = '';
    public $position_id = '';
    public $first_name = '';
    public $last_name = '';
    public $middle_name = '';
    public $suffix = '';
    public $contact_number = '';
    public $email = '';

    // Additional Information
    public $gender = '';
    public $birth_date = '';
    public $tin = '';
    public $blood_type = '';
    public $weight = '';

    // Address
    public $address = '';

    // Emergency Contact
    public $emergency_contact_name = '';
    public $emergency_contact_relationship = '';
    public $emergency_contact_number = '';

    // Image
    public $image;

    // UI State
    public $showPassword = false;
    public $registrationSuccess = false;
    public $successMessage = '';
    public $registrationError = false;
    public $errorMessage = '';

    // Employee Check State
    public $employeeFound = false;
    public $employeeCheckMessage = '';
    public $isExistingEmployee = false;
    public $existingEmployeeId = null;
    public $employeeNumberFormatted = '';
    public $hasExistingAccount = false;
    public $searchedEmployeeNumber = '';

    // I-Support office code constant
    public const I_SUPPORT_OFFICE_CODE = 'I-SUPPORT';

    // Computed property for unit visibility
    public $showUnit = true;

    // Dropdown options
    public $officeCategoryOptions = [];
    public $clusterOptions = [];
    public $regionOptions = [];
    public $offices = [];
    public $units = [];
    public $positions = [];

    // Gender options
    public $genderOptions = [
        'Male',
        'Female',
    ];

    // Blood type options
    public $bloodTypeOptions = [
        'A+',
        'A-',
        'B+',
        'B-',
        'AB+',
        'AB-',
        'O+',
        'O-',
    ];

    // Relationship options
    public $relationshipOptions = [
        'Spouse',
        'Parent',
        'Sibling',
        'Child',
        'Relative',
        'Friend',
        'Other',
    ];

    public function mount()
    {
        // Reset error/success states on initial load
        $this->registrationError = false;
        $this->errorMessage = '';
        $this->loadDropdowns();
    }

    public function loadDropdowns()
    {
        $this->officeCategoryOptions = \App\Models\OfficeCategory::orderBy('name')->get();
        $this->clusterOptions = \App\Models\Cluster::orderBy('name')->get();
        $this->regionOptions = \App\Models\Region::orderBy('name')->get();
        $this->offices = Office::orderBy('name')->get();
        $this->units = [];
        $this->positions = [];
    }

    /**
     * Format employee number with leading zeros to 4 digits
     */
    protected function formatEmployeeNumber(string $employeeNumber): string
    {
        // Remove any leading zeros that might already exist, then pad with zeros
        $number = ltrim($employeeNumber, '0');
        if ($number === '') {
            $number = '0';
        }
        return str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check if employee number exists and if they have a user account
     */
    public function checkEmployee()
    {
        // Reset state
        $this->employeeFound = false;
        $this->employeeCheckMessage = '';
        $this->isExistingEmployee = false;
        $this->existingEmployeeId = null;
        $this->employeeNumberFormatted = '';
        $this->hasExistingAccount = false;

        // Check if employee number is empty
        if (empty($this->employee_number)) {
            // Clear form fields when employee number is empty
            $this->resetFormFields();
            return;
        }

        // Track the searched employee number to prevent unnecessary re-searches
        $this->searchedEmployeeNumber = $this->employee_number;

        // Format employee number with leading zeros to 4 digits
        $this->employeeNumberFormatted = $this->formatEmployeeNumber($this->employee_number);

        // First check if employee has a user account (this should work for any valid employee number)
        $existingUser = User::where('employee_number', $this->employeeNumberFormatted)->first();

        if ($existingUser) {
            // Employee has a user account - show message to login instead
            $this->hasExistingAccount = true;
            $this->employeeCheckMessage = 'You already have an account. Please login instead of registering. Please contact system administrator if there is an error in the system.';
            // Clear form fields - cannot register with existing account
            $this->resetFormFields();
            return;
        }

        // Check if employee exists in the employees table
        $employee = Employee::where('employee_number', $this->employeeNumberFormatted)->first();

        if ($employee) {
            // Employee exists in the database
            $this->employeeFound = true;
            $this->existingEmployeeId = $employee->id;
            $this->isExistingEmployee = true;

            // Employee exists in database but no user account - can register
            $this->hasExistingAccount = false;
            $this->employeeCheckMessage = 'Employee found. Please complete the registration form to create your account.';
            // Clear form fields for new registration
            $this->resetFormFields();
        } else {
            // Employee does not exist - new employee, show empty form for registration
            $this->employeeFound = false;
            $this->employeeCheckMessage = 'New employee number detected. Please complete the registration form.';
            $this->isExistingEmployee = false;
            $this->hasExistingAccount = false;

            // Always keep form empty for new registration
            $this->resetFormFields();
        }
    }

    /**
     * Updated employee number - called automatically when typing
     */
    public function updatedEmployeeNumber()
    {
        // Check employee if we have at least 1 character (will be padded to 4 digits)
        if (strlen($this->employee_number) >= 1) {
            $this->checkEmployee();
        } else {
            // Reset state when employee number is empty
            $this->employeeFound = false;
            $this->employeeCheckMessage = '';
            $this->isExistingEmployee = false;
            $this->existingEmployeeId = null;
            $this->employeeNumberFormatted = '';
            $this->hasExistingAccount = false;
            $this->searchedEmployeeNumber = '';
            $this->resetFormFields();
        }
    }

    /**
     * Reset form fields to empty values
     */
    protected function resetFormFields()
    {
        $this->office_category_id = '';
        $this->cluster_id = '';
        $this->region_id = '';
        $this->office_id = '';
        $this->unit_id = '';
        $this->position_id = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->middle_name = '';
        $this->suffix = '';
        $this->contact_number = '';
        $this->email = '';
        $this->gender = '';
        $this->birth_date = '';
        $this->tin = '';
        $this->blood_type = '';
        $this->weight = '';
        $this->address = '';
        $this->emergency_contact_name = '';
        $this->emergency_contact_relationship = '';
        $this->emergency_contact_number = '';
        $this->image = null;
        $this->units = [];
        $this->positions = [];
    }

    protected function rules()
    {
        // Base rules that always apply - registration is ONLY for new employees
        $rules = [
            'employee_number' => ['required', 'string'],
            'office_category_id' => ['required', 'exists:office_categories,id'],
            'cluster_id' => ['required', 'exists:clusters,id'],
            'region_id' => ['required', 'exists:regions,id'],
            'office_id' => ['required', 'exists:offices,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:50'],
            'contact_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'gender' => ['required', Rule::in($this->genderOptions)],
            'birth_date' => ['required', 'date', 'before:today'],
            'tin' => ['nullable', 'string', 'max:20'],
            'blood_type' => ['required', Rule::in($this->bloodTypeOptions)],
            'weight' => ['required', 'numeric', 'min:1', 'max:500'],
            'address' => ['required', 'string'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_relationship' => ['required', Rule::in($this->relationshipOptions)],
            'emergency_contact_number' => ['required', 'string', 'max:20'],
            // Image is ALWAYS required for new registrations
            'image' => ['required', 'image', 'max:5120'], // 5MB max
        ];

        // Unit is required only for I-SUPPORT office
        $selectedOffice = Office::find($this->office_id);
        if ($selectedOffice && $selectedOffice->code === self::I_SUPPORT_OFFICE_CODE) {
            $rules['unit_id'] = ['required', 'exists:units,id'];
        }

        // Employee number unique rule - only for new employees
        // Use formatted employee number for validation
        if ($this->employeeNumberFormatted) {
            $rules['employee_number'][] = Rule::unique('employees', 'employee_number');
        }

        // Email unique rule - only for new employees
        if ($this->employeeNumberFormatted) {
            $rules['email'][] = Rule::unique('employees', 'email');
        }

        return $rules;
    }
    private function properCase($value)
    {
        if (!$value) return $value;

        return collect(explode(' ', strtolower(trim($value))))
            ->map(fn($word) => ucfirst($word))
            ->implode(' ');
    }

    public function updatedOfficeId()
    {
        // Reset unit and position when office changes
        $this->unit_id = '';
        $this->position_id = '';
        $this->units = [];
        $this->positions = [];

        // Check if selected office is I-Support - ONLY show unit for I-SUPPORT
        $selectedOffice = Office::find($this->office_id);
        if ($selectedOffice && $selectedOffice->code === self::I_SUPPORT_OFFICE_CODE) {
            // I-SUPPORT office - SHOW unit field
            $this->showUnit = true;
            // Load units for I-SUPPORT office
            $this->units = Unit::where('office_id', $this->office_id)->orderBy('name')->get();
        } else {
            // Other offices - HIDE unit field
            $this->showUnit = false;
        }

        // Load ALL positions from database (no filtering by office or unit)
        $this->positions = Position::orderBy('name')->get();
    }

    public function updatedUnitId()
    {
        // Reset position when unit changes
        $this->position_id = '';

        // Load ALL positions from database (no filtering by unit)
        // Positions are now shown regardless of office or unit selection
        $this->positions = Position::orderBy('name')->get();
    }

    public function register()
    {
        // Reset error state before attempting registration
        $this->registrationError = false;
        $this->errorMessage = '';

        // Format employee number first to check for existing accounts
        $employeeNumberFormatted = $this->formatEmployeeNumber($this->employee_number ?? '');

        // Store formatted number for later use
        $this->employeeNumberFormatted = $employeeNumberFormatted;

        // Check if user already has an account in the users table
        $existingUser = User::where('employee_number', $employeeNumberFormatted)->first();

        if ($existingUser) {
            // Prevent registration if user already has an account
            throw ValidationException::withMessages([
                'employee_number' => 'You already have an account in the system. Please login instead of registering. Contact system administrator if there is an error.',
            ]);
        }

        $this->validate();

        // Show confirmation alert

        LivewireAlert::title('Confirm Submission')
            ->text('Are you sure you want to register?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Register')
            ->withCancelButton('No, Cancel')
            ->onConfirm('confirmedRegistration')
            ->show();
    }

    public function confirmedRegistration()
    {
        try {
            // Format employee number
            $employeeNumber = $this->employeeNumberFormatted ?: $this->formatEmployeeNumber($this->employee_number);

            // Map for standard suffix formats
            $suffixMap = [
                'jr' => 'Jr.',
                'junior' => 'Jr.',
                'sr' => 'Sr.',
                'senior' => 'Sr.',
                'ii' => 'II',
                '2' => 'II',
                'iii' => 'III',
                '3' => 'III',
                'iv' => 'IV',
                '4' => 'IV',
                'v' => 'V',
                '5' => 'V',
            ];

            // Format names properly
            $firstName = $this->properCase($this->first_name);
            $lastName = $this->properCase($this->last_name);
            $middleName = $this->properCase($this->middle_name);
            $suffix = $this->suffix ? ($suffixMap[strtolower($this->suffix)] ?? ucwords(strtolower($this->suffix))) : null;

            // Create new employee
            $employee = Employee::create([
                'employee_number' => $employeeNumber,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'middle_name' => $middleName,
                'suffix' => $suffix,
                'contact_number' => $this->contact_number,
                'email' => $this->email,
                'gender' => $this->gender,
                'birth_date' => $this->birth_date,
                'tin' => $this->tin ?: null,
                'blood_type' => $this->blood_type,
                'weight' => $this->weight,
                'address' => $this->address,
                'emergency_contact_name' => $this->emergency_contact_name,
                'emergency_contact_relationship' => $this->emergency_contact_relationship,
                'emergency_contact_number' => $this->emergency_contact_number,
                'office_category_id' => $this->office_category_id,
                'cluster_id' => $this->cluster_id,
                'region_id' => $this->region_id,
                'office_id' => $this->office_id,
                'unit_id' => $this->unit_id ?: null,
                'position_id' => $this->position_id,
                'employment_status' => 'Hired',
                'date_hired' => now()->toDateString(),
            ]);

            // Create user account if it doesn't exist
            $existingUser = User::where('employee_number', $employeeNumber)->first();

            if (!$existingUser) {
                $firstNameInitial = strtoupper(substr($firstName, 0, 1));
                $length = random_int(8, 9); // random length between 8 and 9
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $charactersLength = strlen($characters);
                $password = '';

                for ($i = 0; $i < $length; $i++) {
                    $password .= $characters[random_int(0, $charactersLength - 1)];
                }

                $firstInitial = strtoupper(substr($employee->first_name, 0, 1));
                $lastInitial  = strtolower(substr($employee->last_name, 0, 1));

                $username = $firstInitial . $lastInitial . $employeeNumber;

                $user = User::create([
                    'employee_number' => $employeeNumber,
                    'username' => $username,
                    'password' => Hash::make($password),
                    'status' => 1,
                    'must_change_password' => 0,
                    'employee_id' => $employee->id,
                ]);

                // Assign employee role
                $user->assignRole('employee');

                // Send registration email
                Mail::to($user->email)->send(new RegistrationPassword($user, $password));
            }

            // Save employee image
            if ($this->image) {
                $imagePath = $this->image->store('employees/' . $employee->id, 'public');
                $employee->update(['image' => $imagePath]);
            }

            // Set success state
            $this->registrationSuccess = true;
            $this->successMessage = "Registration successful! Please check your email (including spam/junk folder) for your login credentials.";

            // Reset form except essential data
            $this->resetExcept('registrationSuccess', 'successMessage', 'registrationError', 'errorMessage', 'offices', 'units', 'positions', 'genderOptions', 'bloodTypeOptions', 'relationshipOptions');

            return redirect()->route('login')->with('success', $this->successMessage);
        } catch (\Exception $e) {
            $this->registrationError = true;
            $this->errorMessage = 'Please try again or contact system administrator.';
            \Log::error('Registration failed: ' . $e->getMessage());
            return;
        }
    }


    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function render()
    {
        return view('livewire.register')
            ->layout('components.layouts.guest');
    }
}
