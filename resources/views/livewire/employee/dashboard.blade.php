<div class="space-y-6">
    <flux:breadcrumbs class="mb-6">
        <flux:breadcrumbs.item>Dashboard</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Welcome Section --}}
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white">
        <h1 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="text-blue-100">Here's an overview of your profile and work activities.</p>
    </div>

    {{-- Profile and Organizational Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Personal Profile Summary --}}
        <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
            <flux:heading size="lg" class="dark:text-white mb-4">Personal Profile</flux:heading>
            <div class="flex items-center space-x-4">
                @if ($employeeProfile->image)
                    <img src="{{ asset('storage/' . $employeeProfile->image) }}" alt="Profile"
                        class="w-16 h-16 rounded-full object-cover">
                @else
                    <flux:icon.user-circle class="w-16 h-16 text-zinc-400" />
                @endif
                <div>
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $employeeProfile->full_name }}
                    </h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Employee
                        #{{ $employeeProfile->formatted_employee_number }}</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">
                        {{ $organizationalInfo['position']->name ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-400">
                    <flux:icon.envelope class="w-4 h-4 mr-2" />
                    {{ $employeeProfile->email }}
                </div>
                @if ($employeeProfile->contact_number)
                    <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-400">
                        <flux:icon.device-phone-mobile class="w-4 h-4 mr-2" />
                        {{ $employeeProfile->contact_number }}
                    </div>
                @endif
                <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-400">
                    <flux:icon.briefcase class="w-4 h-4 mr-2" />
                    {{ $employeeProfile->employment_status }}
                </div>
            </div>
        </flux:card>

        {{-- Organizational Information --}}
        <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
            <flux:heading size="lg" class="dark:text-white mb-4">Organizational Information</flux:heading>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Office</p>
                    <p class="font-medium text-zinc-900 dark:text-white">
                        {{ $organizationalInfo['office']->name ?? 'N/A' }}
                        ({{ $organizationalInfo['office']->code ?? 'N/A' }})</p>
                </div>
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Unit</p>
                    <p class="font-medium text-zinc-900 dark:text-white">
                        {{ $organizationalInfo['unit']->name ?? 'N/A' }}
                        ({{ $organizationalInfo['unit']->code ?? 'N/A' }})</p>
                </div>
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Position</p>
                    <p class="font-medium text-zinc-900 dark:text-white">
                        {{ $organizationalInfo['position']->name ?? 'N/A' }}</p>
                </div>
                @if ($employeeProfile->date_hired)
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Date Hired</p>
                        <p class="font-medium text-zinc-900 dark:text-white">
                            {{ $employeeProfile->date_hired->format('M d, Y') }}</p>
                    </div>
                @endif
            </div>
        </flux:card>

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

        <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Last Month</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($stats['last_month_timelogs']) }}</p>
                </div>
                <flux:badge color="indigo" size="lg">
                    <flux:icon.calendar-days class="w-5 h-5" />
                </flux:badge>
            </div>
            <div class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                Last month entries
            </div>
        </flux:card>

        <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Total Hours Worked</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($stats['total_hours_worked'], 2) }} hrs</p>
                </div>
                <flux:badge color="orange" size="lg">
                    <flux:icon.clock class="w-5 h-5" />
                </flux:badge>
            </div>
            <div class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                All time worked hours
            </div>
        </flux:card>
    </div>

    {{-- Quick Actions & Recent Activities --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Quick Actions --}}
        <div class="lg:col-span-1">
            <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md space-y-4">
                <flux:heading size="lg" class="dark:text-white">Quick Actions</flux:heading>

                <div class="space-y-2">
                    <flux:button variant="primary" class="w-full justify-start" icon="plus-circle"
                        href="{{ route('wfh-timelogs') }}">
                        Log New Entry
                    </flux:button>

                    <flux:button variant="outline"
                        class="w-full justify-start dark:text-zinc-200 hover:bg-zinc-200 dark:hover:bg-zinc-700"
                        icon="user-circle" href="{{ route('update-profile') }}">
                        Update Profile
                    </flux:button>

                    <flux:button variant="outline"
                        class="w-full justify-start dark:text-zinc-200 hover:bg-zinc-200 dark:hover:bg-zinc-700"
                        icon="lock-closed" href="{{ route('employee.change-password') }}">
                        Change Password
                    </flux:button>

                    {{-- <flux:button variant="outline"
                        class="w-full justify-start dark:text-zinc-200 hover:bg-zinc-200 dark:hover:bg-zinc-700"
                        icon="megaphone">
                        View Announcements
                    </flux:button> --}}
                </div>
            </flux:card>
        </div>

        {{-- Recent Activities --}}
        <div class="lg:col-span-2">
            <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg" class="dark:text-white">Recent Activities</flux:heading>
                    <flux:button variant="ghost" size="sm" href="{{ route('wfh-timelogs') }}"
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
