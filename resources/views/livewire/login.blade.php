<div class="w-full max-w-md mx-auto">
    <flux:card class="space-y-8 p-8 shadow-lg rounded-2xl">

        {{-- Header --}}
        <div class="text-center space-y-2">

            {{-- Logo --}}
            <img src="{{ asset('assets/images/Scale-Up Logo.png') }}" @dark
                src="{{ asset('assets/images/Scale-Up Logo_White.png') }}" alt="PRDP HRMS Logo"
                class="h-30 mx-auto w-auto object-contain">

            {{-- Title --}}
            <div class="space-y-1">
                <flux:heading size="xl" class="font-semibold tracking-tight text-zinc-900 dark:text-white">
                    PRDP HRMS
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

        {{-- Login Form --}}
        <form wire:submit.prevent="authenticate" class="space-y-6">

            {{-- Login Field --}}
            <flux:field>
                <flux:label class="text-sm font-medium">
                    Employee Number
                </flux:label>

                <flux:input wire:model="login" type="text" placeholder="Enter your employee number" autofocus
                    autocomplete="off" class="h-11">
                    <x-slot name="iconLeading">
                        <flux:icon.user variant="mini" />
                    </x-slot>
                </flux:input>

                <flux:error name="login" />

                <flux:description class="text-xs text-zinc-400">
                    Example: 0001
                </flux:description>
            </flux:field>

            {{-- Password Field --}}
            <flux:field>
                <flux:label class="text-sm font-medium">
                    Password
                </flux:label>

                <flux:input wire:model="password" :type="$showPassword ? 'text' : 'password'"
                    placeholder="Enter your password" class="h-11">
                    <x-slot name="iconLeading">
                        <flux:icon.lock-closed variant="mini" />
                    </x-slot>
                    <x-slot name="iconTrailing">
                        <button type="button" wire:click="togglePassword"
                            class="text-xs text-zinc-500 hover:text-zinc-800 dark:hover:text-white flex items-center gap-1 transition">
                            @if ($showPassword)
                                <flux:icon.eye-slash variant="mini" />
                            @else
                                <flux:icon.eye variant="mini" />
                            @endif
                        </button>
                    </x-slot>
                </flux:input>

                <flux:error name="password" />
            </flux:field>

            {{-- Remember + Forgot --}}
            <div class="flex items-center justify-between text-sm">
                <flux:checkbox wire:model="remember" label="Remember me" />

                <button type="button" wire:click="$set('showForgotModal', true)"
                    class="text-primary-600 hover:text-primary-700 font-medium transition">
                    Forgot password?
                </button>
            </div>

            {{-- Submit --}}
            <flux:button type="submit" variant="primary" class="w-full h-11 text-sm font-medium tracking-wide"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Sign in</span>
                <span wire:loading class="flex items-center justify-center gap-2">
                    <flux:icon.arrow-path class="animate-spin" variant="mini" />
                    Signing in...
                </span>
            </flux:button>
        </form>

        {{-- Register Link --}}
        <div class="text-center text-sm text-zinc-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium transition">
                Register here
            </a>
        </div>

        {{-- Footer --}}
        <div class="text-center text-xs text-zinc-400">
            © {{ date('Y') }} PRDP HRMS. All rights reserved.
        </div>
    </flux:card>

    {{-- Forgot Password Modal --}}
    <flux:modal wire:model="showForgotModal" wire:ignore class="max-w-md p-6 rounded-xl">

        <div class="space-y-2">
            <flux:heading size="lg" class="font-semibold">
                Reset Password
            </flux:heading>
            <flux:subheading class="text-sm text-zinc-500">
                Enter your email and we’ll send a reset link.
            </flux:subheading>
        </div>

        @if ($forgotStatus)
            @if ($isMaxAttemptsMessage)
                <flux:callout variant="danger" icon="exclamation-triangle" class="mt-4">
                    <flux:callout.heading>Too Many Attempts</flux:callout.heading>
                    <flux:callout.text>
                        You've reached the maximum number of password reset attempts for today. Please try again
                        tomorrow or contact the system administrator for assistance.
                    </flux:callout.text>
                </flux:callout>
            @else
                <flux:callout :variant="$forgotStatusVariant ?? 'success'" class="mt-4 text-sm">
                    {{ $forgotStatus }}
                </flux:callout>

                @if (!$maxResendReached)
                    <div class="mt-4 text-center">
                        <button type="button" wire:click="resendResetLink"
                            class="text-sm text-primary-600 hover:text-primary-700 font-medium transition">
                            Didn't receive the email? Resend
                        </button>
                    </div>
                @endif
            @endif
        @endif

        <form wire:submit="sendResetLink" class="mt-6 space-y-4">

            <flux:field>
                <flux:label class="text-sm font-medium">
                    Email Address
                </flux:label>

                <flux:input wire:model="forgotEmail" type="email" placeholder="you@example.com" class="h-11" />

                <flux:error name="forgotEmail" />
            </flux:field>

            <div class="flex justify-end gap-2 pt-2">
                <flux:button type="button" variant="ghost" wire:click="$set('showForgotModal', false)">
                    Cancel
                </flux:button>

                <flux:button type="submit" variant="primary" class="px-5">
                    Send Link
                </flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Change Password Modal --}}
    <flux:modal wire:model="showChangePasswordModal" wire:ignore class="max-w-md p-6 rounded-xl">

        <div class="space-y-2">
            <flux:heading size="lg" class="font-semibold">
                Change Your Password
            </flux:heading>
            <flux:subheading class="text-sm text-zinc-500">
                You must change your temporary password before continuing.
            </flux:subheading>
        </div>

        <form wire:submit="changePassword" class="mt-6 space-y-4">

            <flux:field>
                <flux:label class="text-sm font-medium">
                    New Password
                </flux:label>

                <flux:input wire:model="newPassword" type="password" placeholder="Enter new password" class="h-11" />

                <flux:error name="newPassword" />

                <flux:description class="text-xs text-zinc-400">
                    Minimum 8 characters
                </flux:description>
            </flux:field>

            <flux:field>
                <flux:label class="text-sm font-medium">
                    Confirm New Password
                </flux:label>

                <flux:input wire:model="confirmPassword" type="password" placeholder="Confirm new password"
                    class="h-11" />

                <flux:error name="confirmPassword" />
            </flux:field>

            <div class="flex justify-end gap-2 pt-2">
                <flux:button type="submit" variant="primary" class="px-5">
                    Change Password
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
