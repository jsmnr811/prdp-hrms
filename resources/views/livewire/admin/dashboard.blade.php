<div class="space-y-6 p-6">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">


        <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">System Users</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($stats['total_users']) }}
                    </p>
                </div>
                <flux:badge color="green" size="lg">
                    <flux:icon.users class="w-5 h-5" />
                </flux:badge>
            </div>
            <div class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                {{ $stats['active_users'] }} active users
            </div>
        </flux:card>
        <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Employee Profile</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($stats['total_employees']) }}
                    </p>
                </div>
                <flux:badge color="blue" size="lg">
                    <flux:icon.user-circle class="w-5 h-5" />

                </flux:badge>
            </div>
            <div class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                {{ $stats['active_employees'] }} active • {{ $stats['new_this_month'] }} new this month
            </div>
        </flux:card>

        <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Offices</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($stats['total_offices']) }}
                    </p>
                </div>
                <flux:badge color="purple" size="lg">
                    <flux:icon.building-office class="w-5 h-5" />
                </flux:badge>
            </div>
            <div class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                Total departments
            </div>
        </flux:card>

        <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Positions</p>
                    <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($stats['total_positions']) }}
                    </p>
                </div>
                <flux:badge color="orange" size="lg">
                    <flux:icon.clipboard-document-list class="w-5 h-5" />
                </flux:badge>
            </div>
            <div class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                Job positions
            </div>
        </flux:card>
    </div>

    {{-- Quick Actions & Recent Employees --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Quick Actions --}}
        <div class="lg:col-span-1">
            <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md space-y-4">
                <flux:heading size="lg" class="dark:text-white">Quick Actions</flux:heading>

                <div class="space-y-2">
                    <div class="space-y-2">
                        @can('create-employees')
                        <flux:button variant="primary" class="w-full justify-start text-sm font-medium" icon="user-plus"
                            :href="route('admin.add-employee')">
                            Add New Employee
                        </flux:button>
                        @endcan
                        @can('view-employees')
                        <flux:button variant="outline"
                            class="w-full justify-start text-sm font-medium hover:bg-zinc-100 dark:hover:bg-zinc-700"
                            icon="user-group" :href="route('admin.employee-list')">
                            Manage Employees
                        </flux:button>
                        @endcan
                        <flux:button variant="outline"
                            class="w-full justify-start text-sm font-medium hover:bg-zinc-100 dark:hover:bg-zinc-700"
                            icon="clipboard-document-list" :href="route('admin.activity-logs')">
                            Activity Logs
                        </flux:button>
                    </div>

                    {{-- <flux:button variant="outline"
                        class="w-full justify-start dark:text-zinc-200 hover:bg-zinc-200 dark:hover:bg-zinc-700"
                        icon="envelope" wire:click="confirmSendWelcomeEmails">
                        Send Welcome Emails
                    </flux:button> --}}
                </div>
            </flux:card>
        </div>
        {{-- Recent Employees --}}
        @can('view-employees')
        <div class="lg:col-span-2">
            <flux:card class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg" class="dark:text-white">Recent Employees</flux:heading>
                    <flux:button variant="ghost" size="sm" href="{{ route('admin.employee-list') }}"
                        class="dark:text-zinc-300 hover:underline">
                        View All Employees
                    </flux:button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="text-xs text-zinc-600 uppercase bg-zinc-50 dark:bg-zinc-900 dark:text-zinc-400">
                            <tr>
                                <th class="px-4 py-3">Employee #</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Office</th>
                                <th class="px-4 py-3">Position</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEmployees as $employee)
                            <tr
                                class="border-b dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                                <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                    {{ $employee->formatted_employee_number }}
                                </td>
                                <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ $employee->full_name }}</td>
                                <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                    {{ $employee->office?->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">
                                    {{ $employee->position?->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3">
                                    <flux:badge
                                        :color="
                                                                                                                                                                                    $employee->employment_status === 'Hired' ? 'green' :
                                                                                                                                                                                    ($employee->employment_status === 'Resigned' ? 'red' : 'yellow')
                                            "
                                        size="sm">
                                        {{ $employee->employment_status }}
                                    </flux:badge>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-zinc-600 dark:text-zinc-400">
                                    No employees found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </flux:card>
        </div>
        @endcan

    </div>
</div>