<div class="space-y-6 p-6">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Activity Logs</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:card>
        <flux:heading size="lg" class="mb-6">My Activity Logs</flux:heading>

        <!-- Filters -->
        <flux:fieldset class="mb-6">
            <flux:heading size="md" class="mb-4">Filters</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                        <th class="px-4 py-3">Action</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">IP Address</th>
                        <th class="px-4 py-3">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activityLogs as $log)
                        <tr class="border-b dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                            <td class="px-4 py-3">
                                <flux:badge
                                    :color="
                                        $log->action === 'login' ? 'green' :
                                        ($log->action === 'update_profile' ? 'blue' :
                                        ($log->action === 'change_password' ? 'yellow' :
                                        (str_contains($log->action, 'timelog') ? 'orange' : 'zinc')))
                                    ">
                                    {{ ucwords(str_replace('_', ' ', $log->action)) }}
                                </flux:badge>
                            </td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-zinc-300">
                                {{ $log->description }}
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
                            <td colspan="4" class="px-4 py-3 text-center text-zinc-500 dark:text-zinc-400">
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
