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
                                 <button wire:click="showFullDescription({{ $log->id }})"
                                         class="text-left hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                         title="Click to view full description">
                                     {{ Str::limit($log->description, 100) }}
                                 </button>
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

    <!-- Full Description Modal -->
    @if($showDescriptionModal && $selectedLog)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Activity Log Details</h3>
                    <button wire:click="closeDescriptionModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">User</label>
                            <p class="text-sm text-zinc-900 dark:text-white">{{ $selectedLog->user->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $selectedLog->user->username ?? '' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Action</label>
                            <p class="text-sm text-zinc-900 dark:text-white">{{ ucwords(str_replace('_', ' ', $selectedLog->action)) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">IP Address</label>
                            <p class="text-sm text-zinc-900 dark:text-white">{{ $selectedLog->ip_address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Timestamp</label>
                            <p class="text-sm text-zinc-900 dark:text-white">{{ $selectedLog->created_at->format('M d, Y H:i:s') }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Description</label>
                        <div class="bg-zinc-50 dark:bg-zinc-700 rounded p-3 text-sm text-zinc-900 dark:text-zinc-100">
                            @php
                                $parts = explode('. Changes: ', $selectedLog->description, 2);
                                $mainDesc = $parts[0] . '.';
                                $changes = isset($parts[1]) ? $parts[1] : null;
                            @endphp

                            <p class="mb-2">{{ $mainDesc }}</p>

                            @if($changes)
                                <div class="border-t border-zinc-300 dark:border-zinc-600 pt-2">
                                    <p class="font-medium text-xs text-zinc-600 dark:text-zinc-400 uppercase tracking-wide mb-1">Changes Made:</p>
                                    <div class="space-y-1">
                                        @foreach(explode(', ', $changes) as $change)
                                            <div class="text-xs bg-zinc-100 dark:bg-zinc-600 px-2 py-1 rounded">
                                                {{ $change }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($selectedLog->affectedEmployee)
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Affected Employee</label>
                        <p class="text-sm text-zinc-900 dark:text-white">
                            {{ $selectedLog->affectedEmployee->full_name }} ({{ $selectedLog->affectedEmployee->employee_number }})
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
