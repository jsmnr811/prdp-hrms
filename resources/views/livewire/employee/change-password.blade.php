<div class="space-y-6">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs class="mb-6">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Home</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Change Password</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Success Message --}}
    @if (session('success'))
        <flux:callout variant="success" class="mb-6">
            <flux:callout.text>
                {{ session('success') }}
            </flux:callout.text>
        </flux:callout>
    @endif

    {{-- Change Password Form --}}
    <flux:card class="max-w-lg mx-auto bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
        <div class="p-6" x-data="{
            passwordStrength: 0,
            calculateStrength(password) {
                if (!password) return 0;
                let strength = 0;
                if (password.length >= 8) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                return Math.min(strength, 4);
            }
        }">
            <div class="text-center mb-6">
                <flux:heading size="lg" class="font-semibold">
                    Change Password
                </flux:heading>
                <flux:subheading class="text-sm text-zinc-500 mt-2">
                    Update your account password securely
                </flux:subheading>
            </div>

            <form wire:submit.prevent="confirmChangePassword" class="space-y-4">

                {{-- Current Password Field --}}
                <flux:field>
                    <flux:label>
                        Current Password
                    </flux:label>

                    <flux:input wire:model="currentPassword" :type="$showCurrentPassword ? 'text' : 'password'"
                        placeholder="Enter your current password">
                        <x-slot name="iconLeading">
                            <flux:icon.lock-closed variant="mini" />
                        </x-slot>
                        <x-slot name="iconTrailing">
                            <button type="button" wire:click="toggleCurrentPassword"
                                class="text-xs text-zinc-500 hover:text-zinc-800 dark:hover:text-white flex items-center gap-1 transition">
                                @if ($showCurrentPassword)
                                    <flux:icon.eye-slash variant="mini" />
                                @else
                                    <flux:icon.eye variant="mini" />
                                @endif
                            </button>
                        </x-slot>
                    </flux:input>

                    <flux:error name="currentPassword" />
                </flux:field>

                {{-- New Password Field --}}
                <flux:field>
                    <flux:label>
                        New Password
                    </flux:label>

                    <flux:input wire:model="newPassword"
                        @input="passwordStrength = calculateStrength($event.target.value)"
                        :type="$showNewPassword ? 'text' : 'password'" placeholder="Enter new password">
                        <x-slot name="iconLeading">
                            <flux:icon.lock-closed variant="mini" />
                        </x-slot>
                        <x-slot name="iconTrailing">
                            <button type="button" wire:click="toggleNewPassword"
                                class="text-xs text-zinc-500 hover:text-zinc-800 dark:hover:text-white flex items-center gap-1 transition">
                                @if ($showNewPassword)
                                    <flux:icon.eye-slash variant="mini" />
                                @else
                                    <flux:icon.eye variant="mini" />
                                @endif
                            </button>
                        </x-slot>
                    </flux:input>

                    <flux:error name="newPassword" />

                    {{-- Password Strength Indicator --}}
                    <div class="mt-3">
                        <div class="flex space-x-1 mb-2">
                            @for ($i = 1; $i <= 4; $i++)
                                <div class="h-2 flex-1 rounded-full transition-all duration-300"
                                    x-bind:class="$i <= passwordStrength ? (passwordStrength == 1 ? 'bg-red-400' : passwordStrength ==
                                        2 ? 'bg-orange-400' : passwordStrength == 3 ? 'bg-yellow-400' :
                                        'bg-green-400') : 'bg-gray-200'">
                                </div>
                            @endfor
                        </div>
                        <p class="text-xs mt-1"
                            x-bind:class="passwordStrength == 0 ? 'text-gray-500' : passwordStrength == 1 ? 'text-red-500' :
                                passwordStrength == 2 ? 'text-orange-500' : passwordStrength == 3 ? 'text-yellow-500' :
                                'text-green-500'"
                            x-text=" passwordStrength == 0 ? 'Password strength' : passwordStrength == 1 ? 'Very Weak' : passwordStrength == 2 ? 'Weak' : passwordStrength == 3 ? 'Fair' : 'Strong' ">
                        </p>
                    </div>

                    <flux:description class="text-xs text-gray-500 mt-2">
                        @if (Auth::user()->must_change_password)
                            Minimum 8 characters
                        @else
                            Minimum 8 characters, must be different from current password
                        @endif
                    </flux:description>
                </flux:field>

                {{-- Confirm New Password Field --}}
                <flux:field>
                    <flux:label>
                        Confirm New Password
                    </flux:label>

                    <flux:input wire:model="confirmPassword" :type="$showConfirmPassword ? 'text' : 'password'"
                        placeholder="Confirm new password">
                        <x-slot name="iconLeading">
                            <flux:icon.lock-closed variant="mini" />
                        </x-slot>
                        <x-slot name="iconTrailing">
                            <button type="button" wire:click="toggleConfirmPassword"
                                class="text-xs text-zinc-500 hover:text-zinc-800 dark:hover:text-white flex items-center gap-1 transition">
                                @if ($showConfirmPassword)
                                    <flux:icon.eye-slash variant="mini" />
                                @else
                                    <flux:icon.eye variant="mini" />
                                @endif
                            </button>
                        </x-slot>
                    </flux:input>

                    <flux:error name="confirmPassword" />
                </flux:field>

                {{-- Submit --}}
                <flux:button type="submit" variant="primary" class="w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>Change Password</span>
                    <span wire:loading class="flex items-center justify-center gap-2">
                        <flux:icon.arrow-path class="animate-spin" variant="mini" />
                        Changing...
                    </span>
                </flux:button>
            </form>
        </div>
    </flux:card>
</div>
