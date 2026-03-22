<div class="space-y-6 p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Timelogs</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400">View and manage your work from home time entries</p>
        </div>
        <flux:button variant="primary" icon="plus-circle">
            Add New Entry
        </flux:button>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
                    Search Accomplishments
                </label>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-white"
                    placeholder="Search...">
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
                    Status
                </label>
                <select wire:model.live="status"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-white">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            {{-- Per Page --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
                    Show
                </label>
                <select wire:model.live="perPage"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:text-white">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Timelogs Table --}}
    <div
        class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-zinc-700 dark:text-zinc-300">
                    <tr>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Time In</th>
                        <th class="px-6 py-3">Time Out</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Accomplishments</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($timelogs as $timelog)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $timelog->date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-zinc-300">
                                {{ $timelog->time_in ? $timelog->time_in->format('h:i A') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-zinc-300">
                                {{ $timelog->time_out ? $timelog->time_out->format('h:i A') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
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
                            <td class="px-6 py-4 text-gray-700 dark:text-zinc-300 max-w-xs truncate">
                                {{ $timelog->accomplishments ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <button
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                                        View
                                    </button>
                                    <button
                                        class="text-gray-600 hover:text-gray-800 dark:text-zinc-400 dark:hover:text-zinc-300 text-sm">
                                        Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-zinc-400">
                                <div class="flex flex-col items-center">
                                    <flux:icon.clipboard-document-list class="w-12 h-12 text-gray-400 mb-4" />
                                    <p class="text-lg font-medium">No timelogs found</p>
                                    <p class="text-sm">Start logging your work from home activities.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($timelogs->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-zinc-700 border-t border-gray-200 dark:border-zinc-600">
                {{ $timelogs->links() }}
            </div>
        @endif
    </div>

</div>
