<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-zinc-900">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Admin Dashboard') - PRDP HRMS</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @fluxAppearance
    @livewireStyles
</head>

<body x-data="sidebarApp()" x-init="init()" class="h-full dark:bg-zinc-900 dark:text-zinc-300">
    <div class="h-full flex">
    <div x-data="{ sidebarOpen: false }" class="h-full flex">

        {{-- Mobile Sidebar Overlay --}}
        <div x-show="sidebarOpen"
            class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 dark:bg-zinc-900 dark:bg-opacity-75 lg:hidden"
            x-on:click="sidebarOpen = false"></div>

        {{-- Sidebar --}}
        <div :class="{'block': sidebarOpen, 'hidden': !sidebarOpen}"
            class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 lg:z-50 bg-white dark:bg-zinc-800 border-r border-gray-200 dark:border-zinc-700">

            {{-- Logo --}}
            <div class="flex items-center h-16 px-6 border-b border-gray-200 dark:border-zinc-700">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">PRDP HRMS</span>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg
                        {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-50 dark:text-zinc-300 dark:hover:bg-zinc-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 dark:text-zinc-300 dark:hover:bg-zinc-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Employees
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 dark:text-zinc-300 dark:hover:bg-zinc-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Offices & Units
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 dark:text-zinc-300 dark:hover:bg-zinc-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Positions
                </a>

                <div class="pt-4 mt-4 border-t border-gray-200 dark:border-zinc-700">
                    <p class="px-3 text-xs font-semibold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">User Management</p>
                </div>

                <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 dark:text-zinc-300 dark:hover:bg-zinc-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Users
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 dark:text-zinc-300 dark:hover:bg-zinc-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Roles & Permissions
                </a>

                <div class="pt-4 mt-4 border-t border-gray-200 dark:border-zinc-700">
                    <p class="px-3 text-xs font-semibold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">System</p>
                </div>

                <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 dark:text-zinc-300 dark:hover:bg-zinc-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </a>
            </nav>

            {{-- Theme toggle segmented control --}}
            <div class="my-2 px-4">
                <p class="text-xs font-semibold text-gray-400 dark:text-zinc-500 uppercase tracking-wider mb-1">Theme</p>
                <div class="flex gap-1 rounded-md bg-zinc-700 p-1 text-xs font-semibold text-zinc-300 select-none">
                    <button
                        type="button"
                        @click="setTheme('light')"
                        :class="theme === 'light' ? 'bg-white text-zinc-900' : 'hover:bg-zinc-600'"
                        class="flex-1 rounded-md px-3 py-1 flex items-center justify-center gap-1"
                        :aria-pressed="theme === 'light'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m8.485-8.485h-1M4.515 12.515h-1M16.95 7.05l-.707.707M7.757 16.243l-.707.707M16.95 16.95l-.707-.707M7.757 7.757l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        @click="setTheme('dark')"
                        :class="theme === 'dark' ? 'bg-white text-zinc-900' : 'hover:bg-zinc-600'"
                        class="flex-1 rounded-md px-3 py-1 flex items-center justify-center gap-1"
                        :aria-pressed="theme === 'dark'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        @click="setTheme('system')"
                        :class="theme === 'system' ? 'bg-white text-zinc-900' : 'hover:bg-zinc-600'"
                        class="flex-1 rounded-md px-3 py-1 flex items-center justify-center gap-1"
                        :aria-pressed="theme === 'system'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m8.485-8.485h-1M4.515 12.515h-1M16.95 7.05l-.707.707M7.757 16.243l-.707.707M16.95 16.95l-.707-.707M7.757 7.757l-.707-.707" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- User Profile --}}
            <div class="border-t border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center dark:bg-blue-900">
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-300">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 truncate">#{{ auth()->user()->employee_number }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="lg:pl-64 flex-1 flex flex-col min-w-0 bg-gray-50 dark:bg-zinc-900">
            {{-- Top Header --}}
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200 dark:bg-zinc-800 dark:border-zinc-700 lg:hidden">
                <div class="flex items-center justify-between h-16 px-4">
                    <button x-on:click="sidebarOpen = true" class="text-gray-500 hover:text-gray-600 dark:text-zinc-300 dark:hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">PRDP HRMS</span>
                    <div class="w-6"></div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
</div>
    @livewireScripts
    @fluxScripts

   <script>
        function sidebarApp() {
            return {
                sidebarOpen: false,
                theme: 'system',
                init() {
                    this.theme = localStorage.getItem('theme') || 'system';
                    this.applyTheme();
                    // Listen for system theme changes
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
</body>

</html>