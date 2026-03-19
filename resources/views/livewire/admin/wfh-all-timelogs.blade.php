<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">All WFH Timelogs</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400">View and manage all employee Work From Home timelogs</p>
        </div>
        <button onclick="window.location.href='{{ route('admin.wfh-timelogs.export', [
            'search' => $search,
            'user_id' => $filterUserId,
            'status' => $filterStatus,
            'date_from' => $filterDateFrom,
            'date_to' => $filterDateTo
        ]) }}'"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export PDF
        </button>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('flash.success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg">
            <p class="text-sm text-green-800 dark:text-green-400">{{ session('flash.success') }}</p>
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
                    Search Employee
                </label>
                <input type="text" wire:model="search" placeholder="Name or Employee ID"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Employee Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
                    Employee
                </label>
                <select wire:model="filterUserId"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Employees</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
                    Status
                </label>
                <select wire:model="filterStatus"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            {{-- Date From --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
                    Date From
                </label>
                <input type="date" wire:model="filterDateFrom" wire:change="$refresh"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Date To --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
                    Date To
                </label>
                <input type="date" wire:model="filterDateTo" wire:change="$refresh"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        {{-- Clear Filters --}}
        @if($search || $filterStatus || $filterUserId || $filterDateFrom || $filterDateTo)
            <div class="mt-4 flex justify-end">
                <button wire:click="clearFilters"
                    class="px-4 py-2 text-sm text-gray-600 dark:text-zinc-400 hover:text-gray-900 dark:hover:text-white">
                    Clear Filters
                </button>
            </div>
        @endif
    </div>

    {{-- Timelogs Table --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Employee
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Time In
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Time Out
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Hours
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Location
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            Accomplishments
                        </th>
                    </tr>
                </thead>
                <tbody x-data="{ open: {} }" class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($timelogs as $timelog)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                            {{ $timelog->user->name[0] ?? 'U' }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $timelog->user->name ?? 'Unknown' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-zinc-400">
                                            {{ $timelog->user->employee_id ?? '' }}
                                        </p>
                                    </div>
                                </div>
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
                                @if ($timelog->latitude && $timelog->longitude)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded-full">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @switch($timelog->status)
                                    @case('pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            Pending
                                        </span>
                                    @break
                                    @case('completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                            Completed
                                        </span>
                                    @break
                                @endswitch
                            </td>
                            <td class="px-4 py-3">
                                @if($timelog->accomplishments)
                                    <button
                                        @click="open[{{ $timelog->id }}] = !open[{{ $timelog->id }}]"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                                        x-text="open[{{ $timelog->id }}] ? 'Close' : 'View'">
                                    </button>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @if($timelog->accomplishments)
                        <tr x-show="open[{{ $timelog->id }}]" x-transition class="bg-gray-50 dark:bg-zinc-700/30">
                            <td colspan=8>
                                <div class="px-3 py-2 text-sm text-gray-900 dark:text-zinc-300 max-w-xs">
                                    {{ $timelog->accomplishments }}
                                </div>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-zinc-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-zinc-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p>No timelogs found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($timelogs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
                {{ $timelogs->links() }}
            </div>
        @endif
    </div>
</div>
