<div>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>WFH Management</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Dashboard</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">WFH Dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-zinc-400">Overview of Work From Home timelogs</p>
    </div>

    {{-- Filters --}}
    <div class="mb-6 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filters</h3>
            <button wire:click="clearFilters" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                Clear Filters
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Date From</label>
                <input type="date" wire:model.live="date_from" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Date To</label>
                <input type="date" wire:model.live="date_to" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Office</label>
                <select wire:model.live="office_id" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-white">
                    <option value="">All Offices</option>
                    @foreach($offices as $office)
                    <option value="{{ $office->id }}">{{ $office->code }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Unit</label>
                <select wire:model.live="unit_id" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-white">
                    <option value="">All Units</option>
                    @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->code }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Position</label>
                <select wire:model.live="position_id" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-white">
                    <option value="">All Positions</option>
                    @foreach($positions as $position)
                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Office Category</label>
                <select wire:model.live="office_category_id" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-white">
                    <option value="">All Categories</option>
                    @foreach($officeCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Status</label>
                <select wire:model.live="status" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-white">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Employee Search</label>
                <input type="text" wire:model.live="employee_search" placeholder="Name or Employee #" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-white">
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- Total Timelogs --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Total Timelogs</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTimelogs }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingCount }}</p>
                </div>
                <div
                    class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Completed --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Completed</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $completedCount }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- Today --}}
        <div
            class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-xl shadow-lg p-6 text-white">
            <p class="text-blue-100 text-sm font-medium">Today's Timelogs</p>
            <p class="text-3xl font-bold mt-1">{{ $todayTimelogs->count() }}</p>
            <p class="text-blue-200 text-xs mt-2">{{ now()->format('l, F d, Y') }}</p>
        </div>

        {{-- This Week --}}
        <div
            class="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 rounded-xl shadow-lg p-6 text-white">
            <p class="text-purple-100 text-sm font-medium">This Week</p>
            <p class="text-3xl font-bold mt-1">{{ $weekTimelogs }}</p>
            <p class="text-purple-200 text-xs mt-2">{{ now()->startOfWeek()->format('M d') }} -
                {{ now()->endOfWeek()->format('M d, Y') }}
            </p>
        </div>

        {{-- This Month --}}
        <div
            class="bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 rounded-xl shadow-lg p-6 text-white">
            <p class="text-emerald-100 text-sm font-medium">This Month</p>
            <p class="text-3xl font-bold mt-1">{{ $monthTimelogs }}</p>
            <p class="text-emerald-200 text-xs mt-2">{{ now()->format('F Y') }}</p>
        </div>
    </div>

    {{-- Average Hours Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- Average Hours Today --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Avg Hours Today</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($avgHoursToday, 1) }} hrs</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Average Hours This Week --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Avg Hours This Week</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($avgHoursWeek, 1) }} hrs</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Average Hours This Month --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Avg Hours This Month</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($avgHoursMonth, 1) }} hrs</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Reports --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Timelogs by Office --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Timelogs by Office</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-zinc-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Office</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Pending Count</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Completed Count</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse($pendingByOffice as $date => $offices)
                        @foreach($offices as $office)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">{{ $office['office_name'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">{{ $office['pending_count'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">{{ $office['completed_count'] }}</td>
                        </tr>
                        @endforeach
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-zinc-400">
                                No timelogs
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Timelogs by Unit --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Timelogs by Unit</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-zinc-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Pending Count</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Completed Count</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse($pendingByUnit as $date => $units)
                        @foreach($units as $unit)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">{{ $unit['unit_name'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">{{ $unit['pending_count'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">{{ $unit['completed_count'] }}</td>
                        </tr>
                        @endforeach
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-zinc-400">
                                No timelogs
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Filtered Timelogs Table --}}
    <div
        class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Timelogs</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Employee
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Employee ID
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Office
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Date
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Time In
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Time Out
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Hours
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($filteredTimelogs as $timelog)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                        {{ $timelog->user->name[0] ?? 'U' }}
                                    </span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $timelog->user->name ?? 'Unknown' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">
                            {{ $timelog->user->employee->employee_number ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">
                            {{ $timelog->user->employee->officeCategory->name ?? '-' }} - {{ $timelog->user->employee->unit->code ?? $timelog->user->employee->office->code ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">
                            {{ \Carbon\Carbon::parse($timelog->date)->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">
                            {{ $timelog->time_in ? \Carbon\Carbon::parse($timelog->time_in)->format('h:i A') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">
                            {{ $timelog->time_out ? \Carbon\Carbon::parse($timelog->time_out)->format('h:i A') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-zinc-300">
                            {{ $timelog->total_hours ? number_format($timelog->total_hours, 2) . ' hrs' : '-' }}
                        </td>
                        <td class="px-4 py-3">
                            @switch($timelog->status)
                            @case('pending')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                Pending
                            </span>
                            @break

                            @case('completed')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                Completed
                            </span>
                            @break
                            @endswitch
                        </td>
                        <td class="px-4 py-3">
                            <flux:modal.trigger name="edit-timelog-modal">
                                                <button wire:click="selectTimelog({{ $timelog->id }})"
                                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                    Edit
                                                </button>
                                            </flux:modal.trigger>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500 dark:text-zinc-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 dark:text-zinc-600 mb-2" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p>No timelogs found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Edit Modal --}}
    <flux:modal name="edit-timelog-modal" variant="flyout">
        <div x-show="$wire.showEditModal">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Edit Timelog
            </h3>
            <form wire:submit.prevent="saveTimelog">
                <div class="mb-4">
                    <flux:label for="time_in">Time In</flux:label>
                    <flux:input wire:model="editTimeIn" id="time_in" type="time" />
                    @error('editTimeIn') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
                <div class="mb-4">
                    <flux:label for="time_out">Time Out</flux:label>
                    <flux:input wire:model="editTimeOut" id="time_out" type="time" />
                    @error('editTimeOut') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
                <div class="mb-4">
                    <flux:label for="accomplishments">Accomplishments</flux:label>
                    <flux:textarea wire:model="editAccomplishments" id="accomplishments" rows="3" />
                    @error('editAccomplishments') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
                <div class="flex justify-end space-x-2">
                    <flux:button wire:click="$set('showEditModal', false)" variant="ghost">Cancel</flux:button>
                    <flux:button type="submit">Save</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>