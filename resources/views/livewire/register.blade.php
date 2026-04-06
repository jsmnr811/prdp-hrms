<div class="w-full max-w-4xl mx-auto">
    <flux:card class="space-y-6 p-8 shadow-lg rounded-2xl">

        {{-- Header --}}
        <div class="text-center space-y-2">
            {{-- Logo --}}
            <img src="{{ asset('assets/images/Scale-Up Logo.png') }}" @dark
                src="{{ asset('assets/images/Scale-Up Logo_White.png') }}" alt="PRDP HRMS Logo"
                class="h-30 mx-auto w-auto object-contain">

            {{-- Title --}}
            <div class="space-y-1">
                <flux:heading size="xl" class="font-semibold tracking-tight text-zinc-900 dark:text-white">
                    Employee Registration
                </flux:heading>

                <flux:subheading class="text-sm text-zinc-500">
                    Create your HRMS account
                </flux:subheading>
            </div>
        </div>

        {{-- Existing Account Alert - Shows when user already has an account --}}
        @if ($hasExistingAccount)
            <flux:callout variant="warning" icon="exclamation-triangle" class="mt-4">
                <flux:callout.heading>Account Already Exists</flux:callout.heading>
                <flux:callout.text>
                    You already have a user account in the system. Please <a href="{{ route('login') }}"
                        class="font-semibold underline hover:text-warning-600">login here</a> instead of registering.
                    Please contact system administrator if there is an error in the system.
                </flux:callout.text>
            </flux:callout>
        @endif

        {{-- Employee Check Message --}}
        @if ($employeeCheckMessage && !$hasExistingAccount)
            <flux:callout variant="info" icon="information-circle" class="mt-4">
                {{ $employeeCheckMessage }}
            </flux:callout>
        @endif

        {{-- Success Message --}}
        @if ($registrationSuccess)
            <flux:callout variant="success" icon="check-circle" class="mt-4">
                <flux:callout.heading>Registration Successful!</flux:callout.heading>
                <flux:callout.text>
                   You can now log in. Please check your email (including spam/junk folder) for your login credentials.
                </flux:callout.text>
            </flux:callout>
        @endif

        {{-- Error Message --}}
        @if ($registrationError)
            <flux:callout variant="danger" icon="exclamation-circle" class="mt-4">
                <flux:callout.heading>Registration Failed</flux:callout.heading>
                <flux:callout.text>
                    {{ $errorMessage }}
                </flux:callout.text>
            </flux:callout>
        @endif

        {{-- Registration Form --}}
        <form wire:submit.prevent="register" class="space-y-8">

            {{-- Employee Information Section --}}
            <div class="space-y-4">
                <flux:heading size="md" class="font-semibold text-zinc-900 dark:text-white border-b pb-2">
                    Employee Information
                </flux:heading>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Employee Number --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Employee Number</flux:label>
                        <flux:input wire:model="employee_number" type="text" placeholder="Enter employee number"
                            class="h-11" />
                        <flux:description class="text-xs text-zinc-400">
                            Enter the employee number provided by HR
                        </flux:description>
                        <flux:error name="employee_number" />
                    </flux:field>

                    {{-- Office Category --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Office Category</flux:label>
                        <select wire:model="office_category_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-11">
                            <option value="">Select Office Category</option>
                            @foreach ($officeCategoryOptions as $option)
                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </select>
                        <flux:error name="office_category_id" />
                    </flux:field>

                    {{-- Clusters --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Clusters</flux:label>
                        <select wire:model="cluster_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-11">
                            <option value="">Select Cluster</option>
                            @foreach ($clusterOptions as $option)
                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </select>
                        <flux:error name="cluster_id" />
                    </flux:field>

                    {{-- Region --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Region</flux:label>
                        <select wire:model="region_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-11">
                            <option value="">Select Region</option>
                            @foreach ($regionOptions as $option)
                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </select>
                        <flux:error name="region_id" />
                    </flux:field>

                    {{-- Office/Component --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Office/Component</flux:label>
                        <select wire:model="office_id" wire:change="$refresh"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-11">
                            <option value="">Select Office</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                            @endforeach
                        </select>
                        <flux:error name="office_id" />
                    </flux:field>

                    {{-- Unit - ONLY show for I-SUPPORT office --}}
                    @if ($office_id && $showUnit)
                        <flux:field>
                            <flux:label class="text-sm font-medium">Unit</flux:label>
                            <select wire:model="unit_id" wire:change="$refresh"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-11">
                                <option value="">Select Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                            <flux:error name="unit_id" />
                        </flux:field>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Position --}}
                    @if ($office_id)
                        <flux:field>
                            <flux:label class="text-sm font-medium">Position</flux:label>
                            <select wire:model="position_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-11">
                                <option value="">Select Position</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                            <flux:description class="text-xs text-zinc-400">
                                All available positions
                            </flux:description>
                            <flux:error name="position_id" />
                        </flux:field>
                    @endif
                </div>
            </div>

            {{-- Personal Information Section --}}
            <div class="space-y-4">
                <flux:heading size="md" class="font-semibold text-zinc-900 dark:text-white border-b pb-2">
                    Personal Information
                </flux:heading>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- First Name --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">First Name</flux:label>
                        <flux:input wire:model="first_name" type="text" placeholder="Enter first name"
                            class="h-11" />
                        <flux:error name="first_name" />
                    </flux:field>

                    {{-- Last Name --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Last Name</flux:label>
                        <flux:input wire:model="last_name" type="text" placeholder="Enter last name"
                            class="h-11" />
                        <flux:error name="last_name" />
                    </flux:field>

                    {{-- Middle Name --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Middle Name</flux:label>
                        <flux:input wire:model="middle_name" type="text" placeholder="Enter middle name (optional)"
                            class="h-11" />
                        <flux:error name="middle_name" />
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Suffix --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Suffix</flux:label>
                        <flux:input wire:model="suffix" type="text" placeholder="e.g., Jr., Sr., III"
                            class="h-11" />
                        <flux:description class="text-xs text-zinc-400">
                            Optional (Jr., Sr., etc.)
                        </flux:description>
                        <flux:error name="suffix" />
                    </flux:field>

                    {{-- Gender --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Gender</flux:label>
                        <select wire:model="gender"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-11">
                            <option value="">Select Gender</option>
                            @foreach ($genderOptions as $gender)
                                <option value="{{ $gender }}">{{ $gender }}</option>
                            @endforeach
                        </select>
                        <flux:error name="gender" />
                    </flux:field>

                    {{-- Date of Birth --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Date of Birth</flux:label>
                        <flux:input wire:model="birth_date" type="date" class="h-11" />
                        <flux:error name="birth_date" />
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Contact Number --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Contact Number</flux:label>
                        <flux:input wire:model="contact_number" type="text" placeholder="e.g., 09123456789"
                            class="h-11">
                            <x-slot name="iconLeading">
                                <flux:icon.phone variant="mini" />
                            </x-slot>
                        </flux:input>
                        <flux:error name="contact_number" />
                    </flux:field>

                    {{-- Email Address --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Email Address</flux:label>
                        <flux:input wire:model="email" type="email" placeholder="you@example.com" class="h-11">
                            <x-slot name="iconLeading">
                                <flux:icon.envelope variant="mini" />
                            </x-slot>
                        </flux:input>
                        <flux:error name="email" />
                    </flux:field>

                    {{-- TIN --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Tax ID Number (TIN)</flux:label>
                        <flux:input x-data="{}"
                            x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/(\d{3})(?=\d)/g, '$1-').substring(0, 11)"
                            x-on:keydown="$el.value = $el.value.replace(/[^0-9\-]/g, '')" wire:model="tin"
                            type="text" placeholder="XXX-XXX-XXX" maxlength="11" class="h-11" />
                        <flux:description class="text-xs text-zinc-400">
                            9 digits (format: XXX-XXX-XXX)
                        </flux:description>
                        <flux:error name="tin" />
                    </flux:field>
                </div>
            </div>

            {{-- Additional Information Section --}}
            <div class="space-y-4">
                <flux:heading size="md" class="font-semibold text-zinc-900 dark:text-white border-b pb-2">
                    Additional Information
                </flux:heading>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Blood Type --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Blood Type</flux:label>
                        <select wire:model="blood_type"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-11">
                            <option value="">Select Blood Type</option>
                            @foreach ($bloodTypeOptions as $bloodType)
                                <option value="{{ $bloodType }}">{{ $bloodType }}</option>
                            @endforeach
                        </select>
                        <flux:error name="blood_type" />
                    </flux:field>

                    {{-- Weight --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Weight (Kg)</flux:label>
                        <flux:input wire:model="weight" type="number" step="0.01" placeholder="e.g., 70.5"
                            class="h-11" />
                        <flux:error name="weight" />
                    </flux:field>
                </div>

                {{-- Permanent Address --}}
                <flux:field>
                    <flux:label class="text-sm font-medium">Permanent Address</flux:label>
                    <flux:textarea wire:model="address" placeholder="Enter complete permanent address"
                        rows="3" />
                    <flux:error name="address" />
                </flux:field>
            </div>

            {{-- Emergency Contact Section --}}
            <div class="space-y-4">
                <flux:heading size="md" class="font-semibold text-zinc-900 dark:text-white border-b pb-2">
                    Emergency Contact Information
                </flux:heading>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Emergency Contact Name --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Contact Person Name</flux:label>
                        <flux:input wire:model="emergency_contact_name" type="text" placeholder="Full name"
                            class="h-11">
                            <x-slot name="iconLeading">
                                <flux:icon.user variant="mini" />
                            </x-slot>
                        </flux:input>
                        <flux:error name="emergency_contact_name" />
                    </flux:field>

                    {{-- Relationship --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Relationship</flux:label>
                        <select wire:model="emergency_contact_relationship"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-11">
                            <option value="">Select Relationship</option>
                            @foreach ($relationshipOptions as $relationship)
                                <option value="{{ $relationship }}">{{ $relationship }}</option>
                            @endforeach
                        </select>
                        <flux:error name="emergency_contact_relationship" />
                    </flux:field>

                    {{-- Emergency Contact Number --}}
                    <flux:field>
                        <flux:label class="text-sm font-medium">Contact Number</flux:label>
                        <flux:input wire:model="emergency_contact_number" type="text"
                            placeholder="e.g., 09123456789" class="h-11">
                            <x-slot name="iconLeading">
                                <flux:icon.phone variant="mini" />
                            </x-slot>
                        </flux:input>
                        <flux:error name="emergency_contact_number" />
                    </flux:field>
                </div>
            </div>

            {{-- Photo Section --}}
            <div class="space-y-4">
                <flux:heading size="md" class="font-semibold text-zinc-900 dark:text-white border-b pb-2">
                    Photo
                </flux:heading>

                <flux:field>
                    <flux:label class="text-sm font-medium">Upload Photo</flux:label>
                    <div class="mt-2">
                        <input type="file" wire:model="image" accept="image/*"
                            class="block w-full text-sm text-zinc-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-medium
                                file:bg-primary-100 file:text-primary-700
                                hover:file:bg-primary-200
                                dark:file:bg-primary-900 dark:file:text-primary-300" />
                    </div>
                    <flux:description class="text-xs text-zinc-400">
                        Clear photo with white background (max size 5MB)
                    </flux:description>
                    <flux:error name="image" />

                    {{-- New Image Preview --}}
                    @if ($image)
                        <div class="mt-4">
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-2">Photo Preview:</p>
                            <img src="{{ $image->temporaryUrl() }}" alt="Photo Preview"
                                class="h-32 w-32 object-cover rounded-lg border-2 border-blue-500 dark:border-blue-400" />
                        </div>
                    @endif
                </flux:field>
            </div>

            {{-- Submit Button --}}
            <div class="pt-4">
                <flux:button type="submit" variant="primary" class="w-full h-12 text-sm font-medium tracking-wide"
                    wire:confirm="Is your email address {{ $email }} correct?"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        Register Employee
                    </span>
                    <span wire:loading class="flex items-center justify-center gap-2">
                        <flux:icon.arrow-path class="animate-spin" variant="mini" />
                        Registering...
                    </span>
                </flux:button>
            </div>
        </form>


        {{-- Footer --}}
        <div class="text-center text-xs text-zinc-400 pt-4 border-t">
            © {{ date('Y') }} PRDP HRMS. All rights reserved.
        </div>
    </flux:card>

    {{-- Back to Login Link --}}
    <div class="text-center mt-4">
        <a href="{{ route('login') }}"
            class="text-sm text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 underline">
            Back to Login
        </a>
    </div>
</div>
