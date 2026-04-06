<div class="space-y-6">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs class="mb-6">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Update Profile</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Header --}}
    <div class="text-center space-y-4">
        @if ($this->employee->image)
            <flux:avatar :src="asset('storage/' . $this->employee->image)" alt="Profile" size="xl"
                class="mx-auto shadow-lg ring-4 ring-blue-100 dark:ring-blue-900" />
        @else
            <div class="flex justify-center">
                <flux:icon.user-circle class="w-16 h-16 text-zinc-400" />
            </div>
        @endif
        <flux:heading size="xl" class="font-semibold tracking-tight text-slate-800 dark:text-white">
            Update Profile
        </flux:heading>
        <flux:subheading class="text-sm text-slate-600 dark:text-slate-400">
            Update your personal and contact information
        </flux:subheading>
    </div>

    {{-- Flex Layout: Tabs Sidebar and Content --}}
    <div class="flex flex-col md:flex-row gap-4 md:gap-0">
        {{-- Tab Navigation Sidebar --}}
        <div class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
            <nav class="flex flex-col space-y-2" aria-label="Tabs">
                {{-- Personal Information Tab --}}
                <button wire:click="$set('activeTab', 'personal')" type="button"
                    class="{{ $activeTab === 'personal'
                        ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-800/20 shadow-sm'
                        : 'border-transparent text-slate-600 hover:text-slate-800 hover:bg-slate-100 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-700' }}
                            border-l-4 py-3 px-4 text-sm font-medium transition-all duration-300 w-full text-left rounded-md">
                    <div class="flex items-center gap-2">
                        <flux:icon.user variant="mini" />
                        Personal Information
                    </div>
                </button>

                {{-- Contact Information Tab --}}
                <button wire:click="$set('activeTab', 'contact')" type="button"
                    class="{{ $activeTab === 'contact'
                        ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-800/20 shadow-sm'
                        : 'border-transparent text-slate-600 hover:text-slate-800 hover:bg-slate-100 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-700' }}
                            border-l-4 py-3 px-4 text-sm font-medium transition-all duration-300 w-full text-left rounded-md">
                    <div class="flex items-center gap-2">
                        <flux:icon.phone variant="mini" />
                        Contact Information
                    </div>
                </button>

                {{-- Organizational Information Tab --}}
                <button wire:click="$set('activeTab', 'organizational')" type="button"
                    class="{{ $activeTab === 'organizational'
                        ? 'border-blue-500 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-800/20 shadow-sm'
                        : 'border-transparent text-slate-600 hover:text-slate-800 hover:bg-slate-100 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-700' }}
                            border-l-4 py-3 px-4 text-sm font-medium transition-all duration-300 w-full text-left rounded-md">
                    <div class="flex items-center gap-2">
                        <flux:icon.building-office variant="mini" />
                        Organizational Information
                    </div>
                </button>

            </nav>
        </div>

        {{-- Tab Content --}}
        <div class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md w-full md:w-4/5 pl-0 md:pl-6">
            {{-- Personal Information Tab Panel --}}
            <flux:card x-show="$wire.activeTab === 'personal'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-4"
                class="space-y-6 p-8 bg-white dark:bg-zinc-800 shadow-lg rounded-xl border border-slate-200 dark:border-slate-700">
                {{-- Success Message --}}
                @if (session('personal_message'))
                    <flux:callout variant="success" icon="check-circle" class="mb-4">
                        <flux:callout.heading>Success!</flux:callout.heading>
                        <flux:callout.text>
                            {{ session('personal_message') }}
                        </flux:callout.text>
                    </flux:callout>
                @endif

                <form wire:submit.prevent="confirmPersonal" class="space-y-6">
                    <div class="space-y-4">
                        <flux:heading size="md"
                            class="font-semibold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-600 pb-2">
                            Personal Information
                        </flux:heading>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
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
                                <flux:input wire:model="middle_name" type="text"
                                    placeholder="Enter middle name (optional)" class="h-11" />
                                <flux:error name="middle_name" />
                            </flux:field>

                            {{-- Suffix --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Suffix</flux:label>
                                <flux:input wire:model="suffix" type="text" placeholder="e.g., Jr., Sr., III"
                                    class="h-11" />
                                <flux:error name="suffix" />
                            </flux:field>
                        </div>

                        <flux:separator class="my-6" />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Gender --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Gender</flux:label>
                                <x-select-dropdown wire:model="gender" placeholder="Select Gender" :options="$genderOptions"
                                    class="h-11" />
                                <flux:error name="gender" />
                            </flux:field>

                            {{-- Birth Date --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Birth Date</flux:label>
                                <flux:input wire:model="birth_date" type="date" class="h-11" />
                                <flux:error name="birth_date" />
                            </flux:field>
                        </div>

                        <flux:separator class="my-6" />

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            {{-- TIN --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">TIN</flux:label>
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
                                    <flux:input wire:model="tin"
                                        @input="$wire.set('tin', formatTIN($event.target.value))" type="text"
                                        placeholder="XXX-XXX-XXX" class="h-11" />
                                </div>
                                <flux:error name="tin" />
                            </flux:field>

                            {{-- Blood Type --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Blood Type</flux:label>
                                <x-select-dropdown wire:model="blood_type" placeholder="Select Blood Type"
                                    :options="$bloodTypeOptions" class="h-11" />
                                <flux:error name="blood_type" />
                            </flux:field>

                            {{-- Landbank Account --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Landbank Account</flux:label>
                                <flux:input wire:model="landbank_account" type="text"
                                    placeholder="Enter account number" class="h-11" />
                                <flux:error name="landbank_account" />
                            </flux:field>
                        </div>

                        <flux:separator class="my-6" />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Height --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Height</flux:label>
                                <flux:input wire:model="height" type="number" placeholder="cm" class="h-11" />
                                <flux:error name="height" />
                            </flux:field>

                            {{-- Weight --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Weight</flux:label>
                                <flux:input wire:model="weight" type="number" placeholder="kg" class="h-11" />
                                <flux:error name="weight" />
                            </flux:field>
                        </div>

                        <flux:separator class="my-6" />

                        {{-- Image Upload --}}
                        <flux:field>
                            <flux:label class="text-sm font-medium">Profile Image</flux:label>
                            <div class="mt-2">
                                <input type="file" wire:model="image" accept="image/*"
                                    class="block w-full text-sm text-slate-500
                                            file:mr-4 file:py-3 file:px-4
                                            file:rounded-lg file:border-0
                                            file:text-sm file:font-medium
                                            file:bg-slate-100 file:text-slate-700
                                            hover:file:bg-slate-200
                                            dark:file:bg-slate-800 dark:file:text-slate-300" />
                            </div>
                            <flux:description class="text-sm text-slate-500 dark:text-slate-400">
                                Upload a clear photo (max size 5MB)
                            </flux:description>
                            <flux:error name="image" />

                            {{-- Image Preview --}}
                            @if ($image)
                                <div class="mt-6">
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">New Image
                                        Preview:</p>
                                    <div class="flex justify-center">
                                        <div class="relative group">
                                            <img src="{{ $image->temporaryUrl() }}" alt="Image Preview"
                                                class="h-40 w-40 object-cover rounded-xl border-4 border-blue-200 dark:border-blue-600 shadow-lg transition-transform duration-300 group-hover:scale-105" />
                                            <div
                                                class="absolute inset-0 rounded-xl bg-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($this->employee->image)
                                <div class="mt-6">
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Current
                                        Image:</p>
                                    <div class="flex justify-center">
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $this->employee->image) }}"
                                                alt="Current Image"
                                                class="h-40 w-40 object-cover rounded-xl border-4 border-slate-200 dark:border-slate-600 shadow-lg transition-transform duration-300 group-hover:scale-105" />
                                            <div
                                                class="absolute inset-0 rounded-xl bg-slate-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </flux:field>
                    </div>

                    {{-- Save Button --}}
                    <div class="pt-4">
                        <flux:button type="submit" variant="primary"
                            class="w-full h-12 text-sm font-medium tracking-wide" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                Save Personal Information
                            </span>
                            <span wire:loading class="flex items-center justify-center gap-2">
                                <flux:icon.arrow-path class="animate-spin" variant="mini" />
                                Saving...
                            </span>
                        </flux:button>
                    </div>
                </form>
            </flux:card>

            {{-- Contact Information Tab Panel --}}
            <flux:card x-show="$wire.activeTab === 'contact'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-4"
                class="space-y-6 p-8 bg-white dark:bg-zinc-800 shadow-lg rounded-xl border border-slate-200 dark:border-slate-700">
                {{-- Success Message --}}
                @if (session('contact_message'))
                    <flux:callout variant="success" icon="check-circle" class="mb-4">
                        <flux:callout.heading>Success!</flux:callout.heading>
                        <flux:callout.text>
                            {{ session('contact_message') }}
                        </flux:callout.text>
                    </flux:callout>
                @endif

                <form wire:submit.prevent="confirmContact" class="space-y-6">
                    <div class="space-y-4">
                        <flux:heading size="md"
                            class="font-semibold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-600 pb-2">
                            Contact Information
                        </flux:heading>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Email --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Email Address</flux:label>
                                <flux:input wire:model="email" type="email" placeholder="you@example.com"
                                    class="h-11">
                                    <x-slot name="iconLeading">
                                        <flux:icon.envelope variant="mini" />
                                    </x-slot>
                                </flux:input>
                                <flux:error name="email" />
                            </flux:field>

                            {{-- Contact Number --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Contact Number</flux:label>
                                <flux:input wire:model="contact_number" type="text"
                                    placeholder="e.g., 09123456789" class="h-11">
                                    <x-slot name="iconLeading">
                                        <flux:icon.phone variant="mini" />
                                    </x-slot>
                                </flux:input>
                                <flux:error name="contact_number" />
                            </flux:field>
                        </div>

                        <flux:separator class="my-6" />

                        {{-- Address --}}
                        <flux:field>
                            <flux:label class="text-sm font-medium">Address</flux:label>
                            <flux:textarea wire:model="address" placeholder="Enter complete address"
                                rows="3" />
                            <flux:error name="address" />
                        </flux:field>

                        <flux:separator class="my-6" />

                        {{-- Emergency Contact --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            {{-- Emergency Contact Name --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Emergency Contact Name</flux:label>
                                <flux:input wire:model="emergency_contact_name" type="text"
                                    placeholder="Full name" class="h-11">
                                    <x-slot name="iconLeading">
                                        <flux:icon.user variant="mini" />
                                    </x-slot>
                                </flux:input>
                                <flux:error name="emergency_contact_name" />
                            </flux:field>

                            {{-- Relationship --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Relationship</flux:label>
                                <x-select-dropdown wire:model="emergency_contact_relationship"
                                    placeholder="Select Relationship" :options="$relationshipOptions" class="h-11" />
                                <flux:error name="emergency_contact_relationship" />
                            </flux:field>

                            {{-- Emergency Contact Number --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Emergency Contact Number</flux:label>
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

                    {{-- Save Button --}}
                    <div class="pt-4">
                        <flux:button type="submit" variant="primary"
                            class="w-full h-12 text-sm font-medium tracking-wide" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                Save Contact Information
                            </span>
                            <span wire:loading class="flex items-center justify-center gap-2">
                                <flux:icon.arrow-path class="animate-spin" variant="mini" />
                                Saving...
                            </span>
                        </flux:button>
                    </div>
                </form>
            </flux:card>

            {{-- Organizational Information Tab Panel --}}
            <flux:card x-show="$wire.activeTab === 'organizational'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-4"
                class="space-y-6 p-8 bg-white dark:bg-zinc-900 shadow-lg rounded-xl border border-slate-200 dark:border-slate-700">
                {{-- Success Message --}}
                @if (session('organizational_message'))
                    <flux:callout variant="success" icon="check-circle" class="mb-4">
                        <flux:callout.heading>Success!</flux:callout.heading>
                        <flux:callout.text>
                            {{ session('organizational_message') }}
                        </flux:callout.text>
                    </flux:callout>
                @endif

                <form wire:submit.prevent="confirmOrganizational" class="space-y-6">
                    <div class="space-y-4">
                        <flux:heading size="md"
                            class="font-semibold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-600 pb-2">
                            Organizational Information
                        </flux:heading>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

                            {{-- Office Category --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Office Category</flux:label>
                                <flux:select wire:model="office_category_id" class="h-11">
                                    <option value="">Select Office Category</option>
                                    @foreach ($officeCategoryOptions as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="office_category_id" />
                            </flux:field>

                            {{-- Clusters --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Clusters</flux:label>
                                <flux:select wire:model="cluster_id" class="h-11">
                                    <option value="">Select Cluster</option>
                                    @foreach ($clusterOptions as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="cluster_id" />
                            </flux:field>

                            {{-- Region --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Region</flux:label>
                                <flux:select wire:model="region_id" class="h-11">
                                    <option value="">Select Region</option>
                                    @foreach ($regionOptions as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="region_id" />
                            </flux:field>

                            {{-- Office --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Office</flux:label>
                                <flux:select wire:model="office_id" wire:change="$refresh" class="h-11">
                                    <option value="">Select Office</option>
                                    @foreach ($officeOptions as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </flux:select>
                                <flux:description class="text-xs text-zinc-400 dark:text-zinc-500">
                                    Select your assigned office
                                </flux:description>
                                <flux:error name="office_id" />
                            </flux:field>

                            {{-- Unit (ONLY show for I-SUPPORT office) --}}
                            @if ($office_id && $showUnit)
                                <flux:field>
                                    <flux:label class="text-sm font-medium">Unit</flux:label>

                                    <flux:select wire:model="unit_id" class="h-11">

                                        <option value="">Select Unit</option>

                                        @foreach ($unitOptions as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach

                                    </flux:select>

                                    {{-- Loading indicator --}}
                                    <div wire:loading wire:target="office_id"
                                        class="text-xs text-blue-500 dark:text-blue-400 mt-1">
                                        Loading units...
                                    </div>

                                    <flux:error name="unit_id" />
                                </flux:field>
                            @endif

                            {{-- Position --}}
                            <flux:field>
                                <flux:label class="text-sm font-medium">Position</flux:label>
                                <flux:select wire:model="position_id" class="h-11">
                                    <option value="">Select Position</option>
                                    @foreach ($positionOptions as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="position_id" />
                            </flux:field>

                        </div>
                    </div>

                    {{-- Save Button --}}
                    <div class="pt-4">
                        <flux:button type="submit" variant="primary"
                            class="w-full h-12 text-sm font-medium tracking-wide" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                Save Organizational Information
                            </span>
                            <span wire:loading class="flex items-center justify-center gap-2">
                                <flux:icon.arrow-path class="animate-spin" variant="mini" />
                                Saving...
                            </span>
                        </flux:button>
                    </div>
                </form>
            </flux:card>
        </div>
    </div>

</div>
