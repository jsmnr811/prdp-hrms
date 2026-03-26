<div class="space-y-6 p-6">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Activity Logs</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:card>
        <flux:heading size="lg" class="mb-6">Activity Logs</flux:heading>

        <!-- Filters -->
        <flux:fieldset class="mb-6">
            <flux:heading size="md" class="mb-4">Filters</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <flux:label class="text-sm font-medium py-2">User</flux:label>
                    <flux:select wire:model.live="filterUser" class="h-11">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->username }})</option>
                        @endforeach
                    </flux:select>
                </div>
                <div>
                    <flux:label class="text-sm font-medium py-2">Action</flux:label>
                    <flux:select wire:model.live="filterAction" class="h-11">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}">{{ ucwords(str_replace('_', ' ', $action)) }}</option>
                        @endforeach
                    </flux:select>
                </div>
                <div>
                    <flux:label class="text-sm font-medium py-2">From Date</flux:label>
                    <flux:input wire:model.live="filterDateFrom" type="date" />
                </div>
                <div>
                    <flux:label class="text-sm font-medium py-2">To Date</flux:label>
                    <flux:input wire:model.live="filterDateTo" type="date" />
                </div>
            </div>
            <div class="mt-6">
                <flux:button wire:click="clearFilters" variant="outline">Clear Filters</flux:button>
            </div>
        </flux:fieldset>

        <!-- Activity Logs Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-zinc-600 uppercase bg-zinc-50 dark:bg-zinc-900 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Action</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">IP Address</th>
                        <th class="px-4 py-3">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activityLogs as $log)
                        <tr class="border-b dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                {{ $log->user->name ?? 'Unknown' }}
                                <div class="text-zinc-500 dark:text-zinc-400 text-xs">{{ $log->user->username ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <flux:badge
                                    :color="
                                        $log->action === 'login' ? 'green' :
                                        ($log->action === 'update_profile' ? 'blue' :
                                        ($log->action === 'change_password' ? 'yellow' :
                                        ($log->action === 'add_employee' ? 'purple' :
                                        ($log->action === 'edit_employee' ? 'indigo' :
                                        (str_contains($log->action, 'timelog') ? 'orange' : 'zinc')))))
                                    ">
                                    {{ ucwords(str_replace('_', ' ', $log->action)) }}
                                </flux:badge>
                            </td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-zinc-300">
                                {{ $log->description }}
                                @if($log->affectedUser)
                                    <div class="text-zinc-500 dark:text-zinc-400 text-xs">
                                        Affected User: {{ $log->affectedUser->name }} ({{ $log->affectedUser->username }})
                                    </div>
                                @endif
                                @if($log->affectedEmployee)
                                    <div class="text-zinc-500 dark:text-zinc-400 text-xs">
                                        Affected Employee: {{ $log->affectedEmployee->full_name }} ({{ $log->affectedEmployee->employee_number }})
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-zinc-300">
                                {{ $log->ip_address ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-zinc-300">
                                {{ $log->created_at->format('M d, Y H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-zinc-500 dark:text-zinc-400">
                                No activity logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $activityLogs->links() }}
        </div>
    </flux:card>
</div>
