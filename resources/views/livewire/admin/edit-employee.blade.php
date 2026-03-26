<div class="space-y-6 p-6">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item>User Management</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.employee-list') }}">Employees</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Employee</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md rounded-lg p-6">
        <h2 class="text-lg font-semibold dark:text-white mb-6">Edit Employee</h2>

        <form wire:submit.prevent enctype="multipart/form-data">
            <!-- Personal Information -->
            <flux:fieldset>
                <flux:heading size="lg"
                    class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-600 pb-3 mb-6">
                    Personal Information
                </flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Employee Number -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Employee Number *</flux:label>
                        <flux:input wire:model="employee_number" type="number" min="1" max="9999"
                            placeholder="e.g., 1234" />
                        @error('employee_number')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- First Name -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">First Name *</flux:label>
                        <flux:input wire:model="first_name" placeholder="Enter first name" />
                        @error('first_name')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Last Name *</flux:label>
                        <flux:input wire:model="last_name" placeholder="Enter last name" />
                        @error('last_name')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Middle Name</flux:label>
                        <flux:input wire:model="middle_name" placeholder="Enter middle name" />
                        @error('middle_name')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Suffix -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Suffix</flux:label>
                        <flux:input wire:model="suffix" placeholder="e.g., Jr., Sr., III" />
                        @error('suffix')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Gender *</flux:label>
                        <select wire:model="gender"
                            class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Gender</option>
                            @foreach ($genderOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('gender')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Birth Date *</flux:label>
                        <flux:input wire:model="birth_date" type="date" />
                        @error('birth_date')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- TIN -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">TIN *</flux:label>
                        <div x-data="{
                            formatTIN(value) {
                                let digits = value.replace(/\D/g, '');
                                digits = digits.substring(0, 9);
                                let formatted = '';
                                for (let i = 0; i < digits.length; i++) {
                                    if (i > 0 && i % 3 === 0) formatted += '-';
                                    formatted += digits[i];
                                }
                                return formatted;
                            }
                        }">
                            <flux:input wire:model="tin" @input="$wire.set('tin', formatTIN($event.target.value))"
                                type="text" placeholder="XXX-XXX-XXX" class="h-11" />
                        </div> @error('tin')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Landbank Account -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Landbank Account *</flux:label>
                        <flux:input wire:model="landbank_account" placeholder="Enter landbank account" />
                        @error('landbank_account')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Contact Number -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Contact Number *</flux:label>
                        <flux:input wire:model="contact_number" placeholder="e.g., +63 912 345 6789" />
                        @error('contact_number')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Email *</flux:label>
                        <flux:input wire:model="email" type="email" placeholder="employee@example.com" />
                        @error('email')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <flux:label class="text-sm font-medium py-2">Address *</flux:label>
                        <flux:textarea wire:model="address" rows="3" placeholder="Enter full address" />
                        @error('address')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>
                </div>
            </flux:fieldset>

            <!-- Emergency Contact -->
            <flux:fieldset>
                <flux:heading size="lg"
                    class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-600 pb-3 mb-6 mt-8">
                    Emergency Contact
                </flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4">
                    <!-- Emergency Contact Name -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Emergency Contact Name *</flux:label>
                        <flux:input wire:model="emergency_contact_name" placeholder="Enter emergency contact name" />
                        @error('emergency_contact_name')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Emergency Contact Relationship -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Emergency Contact Relationship *</flux:label>
                        <select wire:model="emergency_contact_relationship"
                            class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Relationship</option>
                            @foreach ($relationshipOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('emergency_contact_relationship')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Emergency Contact Number -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Emergency Contact Number *</flux:label>
                        <flux:input wire:model="emergency_contact_number" placeholder="e.g., +63 912 345 6789" />
                        @error('emergency_contact_number')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>
                </div>
            </flux:fieldset>

            <!-- Organizational Information -->
            <flux:fieldset>
                <flux:heading size="lg"
                    class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-600 pb-3 mb-6 mt-8">
                    Organizational Information
                </flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Office -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Office *</flux:label>
                        <select wire:model.live="office_id"
                            class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Office</option>
                            @foreach ($officeOptions as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                            @endforeach
                        </select>
                        @error('office_id')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Unit -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Unit</flux:label>
                        <select wire:model="unit_id"
                            class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500"
                            {{ empty($unitOptions) ? 'disabled' : '' }}>
                            <option value="">Select Unit</option>
                            @foreach ($unitOptions as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Position *</flux:label>
                        <select wire:model="position_id"
                            class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Position</option>
                            @foreach ($positionOptions as $position)
                                <option value="{{ $position->id }}">{{ $position->name }}</option>
                            @endforeach
                        </select>
                        @error('position_id')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Employment Status -->
                    <div>
                        <flux:label class="text-sm font-medium py-2">Employment Status *</flux:label>
                        <select wire:model="employment_status"
                            class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Status</option>
                            @foreach ($employmentStatusOptions as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('employment_status')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="md:col-span-2">
                        <flux:label class="text-sm font-medium py-2">Role *</flux:label>
                        <select wire:model="role"
                            class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Role</option>
                            @foreach ($roleOptions as $roleOption)
                                <option value="{{ $roleOption }}">{{ $roleOption }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </div>
                </div>
            </flux:fieldset>
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('admin.employee-list') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                <flux:button wire:click="confirmSave" variant="primary" type="button">Update Employee</flux:button>
            </div>
        </form>
    </div>
</div>
