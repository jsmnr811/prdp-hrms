<div class="max-w-md mx-auto">
    <flux:card class="space-y-6 p-8 shadow-lg rounded-2xl">

        {{-- Header --}}
        <div class="text-center space-y-2">

            {{-- Logo --}}
            <img src="{{ asset('assets/images/Scale-Up Logo.png') }}" @dark
                src="{{ asset('assets/images/Scale-Up Logo_White.png') }}" alt="PRDP HRMS Logo"
                class="h-30 mx-auto w-auto object-contain">

            {{-- Title --}}
            <div class="space-y-1">
                <flux:heading size="lg" class="font-semibold">
                    Change Password
                </flux:heading>

                <flux:subheading class="text-sm text-zinc-500">
                    Human Resource Management System
                </flux:subheading>
            </div>

        </div>

        {{-- Success Message --}}
        @if (session('success'))
        <flux:callout variant="success" class="mb-4">
            <flux:callout.text>
                {{ session('success') }}
            </flux:callout.text>
        </flux:callout>
        @endif

        {{-- Change Password Form --}}
        <form wire:submit.prevent="changePassword" class="space-y-4">

            {{-- Current Password Field --}}
            <flux:field>
                <flux:label class="text-sm font-medium">
                    Current Password
                </flux:label>

                <flux:input wire:model="currentPassword" type="password"
                    placeholder="Enter your current password" class="h-11">
                    <x-slot name="iconLeading">
                        <flux:icon.lock-closed variant="mini" />
                    </x-slot>
                </flux:input>

                <flux:error name="currentPassword" />
            </flux:field>

            {{-- New Password Field --}}
            <flux:field>
                <flux:label class="text-sm font-medium">
                    New Password
                </flux:label>

                <flux:input wire:model="newPassword" type="password" placeholder="Enter new password" class="h-11">
                    <x-slot name="iconLeading">
                        <flux:icon.lock-closed variant="mini" />
                    </x-slot>
                </flux:input>

                <flux:error name="newPassword" />

                <flux:description class="text-xs text-zinc-400">
                    @if ($user->must_change_password)
                        Minimum 8 characters
                    @else
                        Minimum 8 characters, must be different from current password
                    @endif
                </flux:description>
            </flux:field>

            {{-- Confirm New Password Field --}}
            <flux:field>
                <flux:label class="text-sm font-medium">
                    Confirm New Password
                </flux:label>

                <flux:input wire:model="confirmPassword" type="password" placeholder="Confirm new password"
                    class="h-11">
                    <x-slot name="iconLeading">
                        <flux:icon.lock-closed variant="mini" />
                    </x-slot>
                </flux:input>

                <flux:error name="confirmPassword" />
            </flux:field>

            {{-- Submit --}}
            <flux:button type="submit" variant="primary" class="w-full h-11 text-sm font-medium tracking-wide"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Change Password</span>
                <span wire:loading class="flex items-center justify-center gap-2">
                    <flux:icon.arrow-path class="animate-spin" variant="mini" />
                    Changing...
                </span>
            </flux:button>
        </form>

        {{-- Footer --}}
        <div class="text-center text-xs text-zinc-400">
            © {{ date('Y') }} PRDP HRMS. All rights reserved.
        </div>
    </flux:card>
</div>