<div class="space-y-6 p-6">

    <flux:breadcrumbs>
        <flux:breadcrumbs.item>Admin</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.regions') }}">Regions</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filters and Search --}}
    <div class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold dark:text-white">Regions</h2>
            <flux:modal.trigger name="add-region-modal">
                <button wire:click="openAddModal"
                    class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Add Region
                    <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </flux:modal.trigger>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Search --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Search</label>
                <input wire:model.live="search" placeholder="Search regions..."
                    class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500" />
            </div>
        </div>
    </div>

    {{-- Regions Table --}}
    <div class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md rounded-lg p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-zinc-600 uppercase bg-zinc-50 dark:bg-zinc-900 dark:text-zinc-400">
                    <tr>
                        <th wire:click="sortBy('name')"
                            class="px-2 py-3 cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            Region Name
                            @if ($sortField === 'name')
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th class="px-2 py-3">Cluster Name</th>
                        <th class="px-2 py-3">Description</th>
                        <th class="px-2 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($regions as $region)
                        <tr
                            class="border-b dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                            <td class="px-2 py-3 font-medium text-zinc-900 dark:text-white">
                                {{ $region->name }}
                            </td>
                            <td class="px-2 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ $region->cluster->name ?? '-' }}
                            </td>
                            <td class="px-2 py-3 text-zinc-700 dark:text-zinc-300">
                                {{ $region->description ?? '-' }}
                            </td>
                            <td class="px-2 py-3 relative">
                                <div x-data="{ open: false }" class="inline-block text-left">
                                    <button @click="open = !open"
                                        class="inline-flex justify-center w-full px-3 py-1 text-sm font-medium bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Actions
                                        <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <div x-show="open" @click.away="open = false"
                                        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-zinc-700 ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95">
                                        <div class="py-1">
                                            <flux:modal.trigger name="edit-region-modal">
                                                <button wire:click="openEditModal({{ $region->id }})"
                                                    class="block w-full text-left px-2 py-2 text-sm text-zinc-700 dark:text-zinc-100 hover:bg-gray-100 dark:hover:bg-zinc-600">
                                                    Edit Region
                                                </button>
                                            </flux:modal.trigger>
                                            <flux:modal.trigger name="delete-region-modal">
                                                <button wire:click="delete({{ $region->id }})"
                                                    class="block w-full text-left px-2 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-zinc-600">
                                                    Delete Region
                                                </button>
                                            </flux:modal.trigger>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-2 py-8 text-center text-zinc-600 dark:text-zinc-400">
                                No regions found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $regions->links() }}
        </div>
    </div>

    {{-- Add Modal --}}
    <flux:modal name="add-region-modal" variant="flyout">
        <div x-show="$wire.showAddModal">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Add Region
            </h3>
            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <flux:label for="cluster_id">Cluster *</flux:label>
                    <select wire:model="cluster_id" id="cluster_id"
                        class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Cluster</option>
                        @foreach($clusters as $cluster)
                            <option value="{{ $cluster->id }}">{{ $cluster->name }}</option>
                        @endforeach
                    </select>
                    @error('cluster_id') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
                <div class="mb-4">
                    <flux:label for="name">Region Name *</flux:label>
                    <flux:input wire:model="name" id="name" type="text" placeholder="Enter region name" required />
                    @error('name') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
                <div class="mb-4">
                    <flux:label for="description">Description</flux:label>
                    <flux:input wire:model="description" id="description" type="text" placeholder="Enter description"  />
                    @error('description') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
                <div class="flex justify-end space-x-2">
                    <flux:button wire:click="closeAddModal" variant="ghost">Cancel</flux:button>
                    <flux:button type="submit">Add Region</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    {{-- Edit Modal --}}
    <flux:modal name="edit-region-modal" variant="flyout">
        <div x-show="$wire.showEditModal">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Edit Region
            </h3>
            <form wire:submit.prevent="update">
                <div class="mb-4">
                    <flux:label for="cluster_id">Cluster *</flux:label>
                    <select wire:model="cluster_id" id="cluster_id"
                        class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Cluster</option>
                        @foreach($clusters as $cluster)
                            <option value="{{ $cluster->id }}">{{ $cluster->name }}</option>
                        @endforeach
                    </select>
                    @error('cluster_id') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
                <div class="mb-4">
                    <flux:label for="name">Region Name *</flux:label>
                    <flux:input wire:model="name" id="name" type="text" placeholder="Enter region name" required />
                    @error('name') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
                <div class="mb-4">
                    <flux:label for="description">Description</flux:label>
                    <flux:input wire:model="description" id="description" type="text" placeholder="Enter description"  />
                    @error('description') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
                <div class="flex justify-end space-x-2">
                    <flux:button wire:click="closeEditModal" variant="ghost">Cancel</flux:button>
                    <flux:button type="submit">Update Region</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    {{-- Delete Modal --}}
    <flux:modal name="delete-region-modal">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            Confirm Deletion
        </h3>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
            Are you sure you want to delete this region? This action cannot be undone.
        </p>
        <div class="flex justify-end space-x-2">
            <flux:button wire:click="closeModals" variant="ghost">Cancel</flux:button>
            <flux:button wire:click="confirmedDelete" variant="danger">Delete Region</flux:button>
        </div>
    </flux:modal>

</div>
