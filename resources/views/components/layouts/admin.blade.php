<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Admin Dashboard') - PRDP HRMS</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @fluxAppearance
    @livewireStyles
    @filepondScripts
</head>

<body class="min-h-screen bg-gray-50 dark:bg-zinc-900">
    <div class="flex min-h-screen">
        <flux:sidebar sticky collapsible="mobile"
            class="border-e border-gray-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
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
                    <flux:sidebar.item icon="squares-2x2" :href="route('admin.dashboard')"
                        :current="request()->routeIs('admin.dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="clock" :href="route('admin.wfh-timelogs')"
                        :current="request()->routeIs('admin.wfh-timelogs')" wire:navigate>
                        {{ __('My TimeLogs') }}
                    </flux:sidebar.item>



                    <flux:sidebar.item icon="users" href="#">
                        {{ __('Employees') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="building-office-2" href="#">
                        {{ __('Offices & Units') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="briefcase" href="#">
                        {{ __('Positions') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('User Management')" class="grid">
                    <flux:sidebar.item icon="user" href="#">
                        {{ __('Users') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="shield-check" href="#">
                        {{ __('Roles & Permissions') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('System')" class="grid">
                    <flux:sidebar.item icon="cog" href="#">
                        {{ __('Settings') }}
                    </flux:sidebar.item>
                      <flux:sidebar.group expandable heading="WFH Management" class="grid">
                        <flux:sidebar.item href="{{ route('admin.wfh-dashboard') }}" wire:navigate>Dashboard</flux:sidebar.item>
                        <flux:sidebar.item href="{{ route('admin.wfh-all-timelogs') }}" wire:navigate>All TimeLogs</flux:sidebar.item>
                        <flux:sidebar.item href="{{ route('admin.wfh-monitoring') }}" wire:navigate>Monitoring</flux:sidebar.item>
                    </flux:sidebar.group>
                </flux:sidebar.group>
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
                    <div
                        class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center dark:bg-blue-900 shrink-0">
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

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Mobile Header --}}
            <flux:header class="lg:hidden border-b border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                <flux:sidebar.toggle icon="bars-2" inset="left" />

                <flux:spacer />

                <span class="text-lg font-bold text-gray-900 dark:text-white">PRDP HRMS</span>

                <flux:spacer />
            </flux:header>

            {{-- Page Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
    @fluxScripts
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        function sidebarApp() {
            return {
                theme: 'system',
                init() {
                    this.theme = localStorage.getItem('theme') || 'system';
                    this.applyTheme();
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                        if (this.theme === 'system') this.applyTheme();
                    });
                },
                setTheme(value) {
                    this.theme = value;
                    localStorage.setItem('theme', value);
                    this.applyTheme();
                },
                applyTheme() {
                    if (this.theme === 'light') {
                        document.documentElement.classList.remove('dark');
                    } else if (this.theme === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    }
                }
            }
        }
    </script>
    @stack('scripts')
</body>

</html>
