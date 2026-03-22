<div class="space-y-6 p-6">

    {{-- Welcome Section --}}
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white">
        <h1 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="text-blue-100">Here's an overview of your work from home activities.</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Total Timelogs</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($stats['total_timelogs']) }}</p>
                </div>
                <flux:badge color="blue" size="lg">
                    <flux:icon.clipboard-document-list class="w-5 h-5" />
                </flux:badge>
            </div>
            <div class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                All time entries
            </div>
        </flux:card>

        <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Pending Approval</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($stats['pending_timelogs']) }}</p>
                </div>
                <flux:badge color="yellow" size="lg">
                    <flux:icon.clock class="w-5 h-5" />
                </flux:badge>
            </div>
            <div class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                Awaiting review
            </div>
        </flux:card>

        <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Approved</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($stats['approved_timelogs']) }}</p>
                </div>
                <flux:badge color="green" size="lg">
                    <flux:icon.check-circle class="w-5 h-5" />
                </flux:badge>
            </div>
            <div class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                Successfully approved
            </div>
        </flux:card>

        <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">This Month</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($stats['this_month_timelogs']) }}</p>
                </div>
                <flux:badge color="purple" size="lg">
                    <flux:icon.calendar-days class="w-5 h-5" />
                </flux:badge>
            </div>
            <div class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                Current month entries
            </div>
        </flux:card>
    </div>

    {{-- Quick Actions & Recent Timelogs --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Quick Actions --}}
        <div class="lg:col-span-1">
            <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md space-y-4">
                <flux:heading size="lg" class="dark:text-white">Quick Actions</flux:heading>

                <div class="space-y-2">
                    <flux:button variant="primary" class="w-full justify-start" icon="plus-circle"
                        href="{{ route('employee.my-timelogs') }}">
                        Log New Entry
                    </flux:button>

                    <flux:button variant="outline"
                        class="w-full justify-start dark:text-zinc-200 hover:bg-zinc-200 dark:hover:bg-zinc-700"
                        icon="clipboard-document-list" href="{{ route('employee.my-timelogs') }}">
                        View My Timelogs
                    </flux:button>

                    <flux:button variant="outline"
                        class="w-full justify-start dark:text-zinc-200 hover:bg-zinc-200 dark:hover:bg-zinc-700"
                        icon="user-circle">
                        Update Profile
                    </flux:button>
                </div>
            </flux:card>
        </div>

        {{-- Recent Timelogs --}}
        <div class="lg:col-span-2">
            <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg" class="dark:text-white">Recent Timelogs</flux:heading>
                    <flux:button variant="ghost" size="sm" href="{{ route('employee.my-timelogs') }}"
                        class="dark:text-zinc-300 hover:underline">
                        View All
                    </flux:button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="text-xs text-zinc-600 uppercase bg-zinc-50 dark:bg-zinc-900 dark:text-zinc-400">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Time In</th>
                                <th class="px-4 py-3">Time Out</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Accomplishments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTimelogs as $timelog)
                                <tr
                                    class="border-b dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                        {{ $timelog->date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-zinc-900 dark:text-white">
                                        {{ $timelog->time_in ? $timelog->time_in->format('H:i') : 'N/A' }}</td>
                                    <td class="px-4 py-3 text-zinc-900 dark:text-white">
                                        {{ $timelog->time_out ? $timelog->time_out->format('H:i') : 'N/A' }}</td>
                                    <td class="px-4 py-3">
                                        <flux:badge
                                            :color="
                                                                                            $timelog->status === 'approved' ? 'green' :
                                                                                            ($timelog->status === 'rejected' ? 'red' :
                                                                                            ($timelog->status === 'pending' ? 'yellow' : 'gray'))
                                            "
                                            size="sm">
                                            {{ ucfirst($timelog->status) }}
                                        </flux:badge>
                                    </td>
                                    <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300 max-w-xs truncate">
                                        {{ $timelog->accomplishments ?? 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-zinc-600 dark:text-zinc-400">
                                        No timelogs found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </flux:card>
        </div>

    </div>
</div>
