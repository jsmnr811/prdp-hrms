<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Employee Dashboard') - PRDP HRMS</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @fluxAppearance
    @livewireStyles
    @filepondScripts
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <div class="flex min-h-screen">
        <x-sidebar class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900" />

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
