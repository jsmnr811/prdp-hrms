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

                <a href="{{ route('password.request') }}"
                    class="text-primary-600 hover:text-primary-700 font-medium transition">
                    Forgot password?
                </a>
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


</div>