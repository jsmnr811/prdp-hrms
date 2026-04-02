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
    <!-- @filepondScripts -->
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

    @if(auth()->check() && \Lab404\Impersonate\Impersonate::isImpersonating())
       <div class="fixed bottom-5 right-5 z-50 flex items-center gap-2 px-3 py-1.5
            rounded-full bg-red-600/10 border border-red-500/20
            backdrop-blur-[2px] text-white shadow-sm">

    <!-- Status Dot -->
    <span class="relative flex h-2 w-2">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-60"></span>
        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
    </span>

    <!-- Text -->
    <span class="text-xs font-medium text-white/80">
        Impersonating
    </span>

    <!-- Divider -->
    <span class="w-px h-3 bg-white/20"></span>

    <!-- Exit Icon -->
    <a href="{{ route('impersonate.leave') }}"
       class="flex items-center justify-center w-5 h-5 rounded
              hover:bg-red-600/20 transition"
       title="Exit impersonation"
    >
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-3.5 w-3.5 text-white/70 hover:text-white"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 18L18 6M6 6l12 12" />
        </svg>
    </a>

</div>
    @endif
</body>

</html>
