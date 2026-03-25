<div class="w-full max-w-md mx-auto" >
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
                    Reset Password
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

        {{-- Back to Login Link --}}
        <div class="text-center text-sm text-zinc-500">
            Remember your password?
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium transition">
                Back to login
            </a>
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

        @endif
        @endif

        <form wire:submit="sendResetLink" class="space-y-4">

            <flux:field>
                <flux:label class="text-sm font-medium">
                    Email Address
                </flux:label>

                <flux:input wire:model="forgotEmail" type="email" placeholder="you@example.com" class="h-11" />

                <flux:error name="forgotEmail" />

                <flux:description class="text-xs text-zinc-400">
                    Enter the email address associated with your account
                </flux:description>
            </flux:field>

            <flux:button
                type="submit"
                variant="primary"
                class="w-full h-12 text-sm font-semibold tracking-wide rounded-lg transition-all duration-200 ease-in-out shadow-md hover:shadow-lg flex items-center justify-center gap-2"
                wire:loading.attr="disabled"
                wire:target="sendResetLink"
                :disabled="$countdown > 0">
                {{-- Normal / Countdown State --}}
                <span wire:loading.remove wire:target="sendResetLink">
                    @if(!$resetSentAt)
                    Send Reset Link
                    @else
                    Resend
                    @if($countdown)
                    ({{ $countdown }}s)
                    @endif
                    @endif
                </span>

                {{-- Loading State --}}
                <span wire:loading wire:target="sendResetLink" class="flex items-center justify-center gap-2">
                    <flux:icon.arrow-path class="animate-spin text-white" variant="mini" />
                    Sending...
                </span>
            </flux:button>
        </form>

        {{-- Footer --}}
        <div class="text-center text-xs text-zinc-400">
            © {{ date('Y') }} PRDP HRMS. All rights reserved.
        </div>
    </flux:card>
</div>