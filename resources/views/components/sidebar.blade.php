<flux:sidebar sticky collapsible="mobile" {{ $attributes }}>
    <flux:sidebar.header>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <span class="text-lg font-bold text-gray-900 dark:text-white">PRDP HRMS</span>
        </div>
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Main')" class="grid">
            @role('administrator')
                <flux:sidebar.item icon="squares-2x2" :href="route('admin.dashboard')"
                    :current="request()->routeIs('admin.dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>
            @endrole

            @role('employee')
                <flux:sidebar.item icon="squares-2x2" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="clock"
                    :href="auth()->user()->hasRole('admin') ? route('admin.wfh-timelogs') : route('wfh-timelogs')"
                    :current="request()->routeIs(auth()->user()->hasRole('admin') ? 'admin.wfh-timelogs' : 'wfh-timelogs')"
                    wire:navigate>
                    {{ __('My TimeLogs') }}
                </flux:sidebar.item>
            @endrole

            @role('administrator')
                <flux:sidebar.item icon="building-office-2">
                    {{ __('Offices & Units') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="briefcase">
                    {{ __('Positions') }}
                </flux:sidebar.item>
            @endrole
        </flux:sidebar.group>

        @role('administrator')
            <flux:sidebar.group :heading="__('User Management')" class="grid">

                <flux:sidebar.item icon="users" :href="route('admin.employee-list')"
                    :current="request()->routeIs('admin.employee-list')" wire:navigate>
                    {{ __('Employees') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="shield-check" :href="route('admin.role-permission-management')"
                    :current="request()->routeIs('admin.role-permission-management')" wire:navigate>
                    {{ __('Roles & Permissions') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="building-office-2" :href="route('admin.clusters')" :current="request()->routeIs('admin.clusters')" wire:navigate>{{ __('Cluster Management') }}</flux:sidebar.item>

                <flux:sidebar.item icon="map" :href="route('admin.regions')" :current="request()->routeIs('admin.regions')" wire:navigate>{{ __('Regions') }}</flux:sidebar.item>

            </flux:sidebar.group>
            <flux:sidebar.group :heading="__('System')" class="grid">
                <flux:sidebar.item icon="cog">
                    {{ __('Settings') }}
                </flux:sidebar.item>
                <flux:sidebar.group expandable heading="WFH Management" class="grid">
                    <flux:sidebar.item href="{{ route('admin.wfh-dashboard') }}" wire:navigate>Dashboard
                    </flux:sidebar.item>
                    <flux:sidebar.item href="{{ route('admin.wfh-all-timelogs') }}" wire:navigate>All TimeLogs
                    </flux:sidebar.item>
                    @if (config('wfh.require_location'))
                        <flux:sidebar.item href="{{ route('admin.wfh-monitoring') }}" wire:navigate>
                            Monitoring
                        </flux:sidebar.item>
                    @endif
                </flux:sidebar.group>
            </flux:sidebar.group>
        @endrole

    </flux:sidebar.nav>

    <flux:spacer />

    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
        <flux:radio value="light" icon="sun" />
        <flux:radio value="dark" icon="moon" />
        <flux:radio value="system" icon="computer-desktop" />
    </flux:radio.group>
    {{-- User Profile --}}
    <div class="border-t border-gray-200 dark:border-zinc-700 p-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center dark:bg-blue-900 shrink-0">
                <span
                    class="text-sm font-medium text-blue-600 dark:text-blue-300">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-zinc-400 truncate">
                    #{{ auth()->user()->employee_number }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-200 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</flux:sidebar>
