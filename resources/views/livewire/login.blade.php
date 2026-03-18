<div class="w-full max-w-md mx-auto">
    <flux:card class="space-y-8 p-8 shadow-lg rounded-2xl">

        {{-- Header --}}
        <div class="text-center space-y-2">

            {{-- Logo --}}
            <img
                src="{{ asset('assets/images/Scale-Up Logo.png') }}"
                @dark src="{{ asset('assets/images/Scale-Up Logo_White.png') }}"
                alt="PRDP HRMS Logo"
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

        {{-- Login Form --}}
        <form wire:submit.prevent="authenticate" class="space-y-6">

            {{-- Login Field --}}
            <flux:field>
                <flux:label class="text-sm font-medium">
                    Email, Username, or Employee Number
                </flux:label>

                <flux:input
                    wire:model="login"
                    type="text"
                    placeholder="Enter your login credentials"
                    autofocus
                    autocomplete="off"
                    class="h-11">
                    <x-slot name="iconLeading">
                        <flux:icon.user variant="mini" />
                    </x-slot>
                </flux:input>

                <flux:error name="login" />

                <flux:description class="text-xs text-zinc-400">
                    Example: email, username, or employee no. (1 → 0001)
                </flux:description>
            </flux:field>

            {{-- Password Field --}}
            <flux:field>
                <div class="flex items-center justify-between">
                    <flux:label class="text-sm font-medium">
                        Password
                    </flux:label>

                    <button
                        type="button"
                        wire:click="togglePassword"
                        class="text-xs text-zinc-500 hover:text-zinc-800 dark:hover:text-white flex items-center gap-1 transition">
                        @if($showPassword)
                        <flux:icon.eye-slash variant="mini" />
                        Hide
                        @else
                        <flux:icon.eye variant="mini" />
                        Show
                        @endif
                    </button>
                </div>

                <flux:input
                    wire:model="password"
                    :type="$showPassword ? 'text' : 'password'"
                    placeholder="Enter your password"
                    class="h-11">
                    <x-slot name="iconLeading">
                        <flux:icon.lock-closed variant="mini" />
                    </x-slot>
                </flux:input>

                <flux:error name="password" />
            </flux:field>

            {{-- Remember + Forgot --}}
            <div class="flex items-center justify-between text-sm">
                <flux:checkbox
                    wire:model="remember"
                    label="Remember me" />

                <button
                    type="button"
                    wire:click="$set('showForgotModal', true)"
                    class="text-primary-600 hover:text-primary-700 font-medium transition">
                    Forgot password?
                </button>
            </div>

            {{-- Submit --}}
            <flux:button
                type="submit"
                variant="primary"
                class="w-full h-11 text-sm font-medium tracking-wide"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Sign in</span>
                <span wire:loading class="flex items-center justify-center gap-2">
                    <flux:icon.arrow-path class="animate-spin" variant="mini" />
                    Signing in...
                </span>
            </flux:button>
        </form>

        {{-- Footer --}}
        <div class="text-center text-xs text-zinc-400">
            © {{ date('Y') }} PRDP HRMS. All rights reserved.
        </div>
    </flux:card>

    {{-- Forgot Password Modal --}}
    <flux:modal wire:model="showForgotModal" class="max-w-md p-6 rounded-xl">

        <div class="space-y-2">
            <flux:heading size="lg" class="font-semibold">
                Reset Password
            </flux:heading>
            <flux:subheading class="text-sm text-zinc-500">
                Enter your email and we’ll send a reset link.
            </flux:subheading>
        </div>

        @if($forgotStatus)
        <flux:callout variant="success" class="mt-4 text-sm">
            {{ $forgotStatus }}
        </flux:callout>
        @endif

        <form wire:submit="sendResetLink" class="mt-6 space-y-4">

            <flux:field>
                <flux:label class="text-sm font-medium">
                    Email Address
                </flux:label>

                <flux:input
                    wire:model="forgotEmail"
                    type="email"
                    placeholder="you@example.com"
                    class="h-11" />

                <flux:error name="forgotEmail" />
            </flux:field>

            <div class="flex justify-end gap-2 pt-2">
                <flux:button
                    type="button"
                    variant="ghost"
                    wire:click="$set('showForgotModal', false)">
                    Cancel
                </flux:button>

                <flux:button
                    type="submit"
                    variant="primary"
                    class="px-5">
                    Send Link
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
