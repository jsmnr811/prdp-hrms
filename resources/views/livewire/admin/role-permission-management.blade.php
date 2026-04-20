<div x-data class="space-y-6 sm:space-y-8 p-4 sm:p-6 lg:p-8">

    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Role & Permission Management</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
        <flux:icon.shield-check class="h-8 w-8 text-zinc-300 flex-shrink-0" />
        <flux:heading size="xl">Role & Permission Management</flux:heading>
    </div>

    <flux:card class="bg-zinc-800 shadow-xl border-0">
        <div class="p-8">

            <!-- Tabs -->
            <flux:button.group class="mb-8">
                <flux:button icon="user-group" variant="{{ $activeTab === 'roles' ? 'primary' : 'ghost' }}"
                    wire:click="setActiveTab('roles')">
                    Roles
                </flux:button>
                <flux:button icon="key" variant="{{ $activeTab === 'permissions' ? 'primary' : 'ghost' }}"
                    wire:click="setActiveTab('permissions')">
                    Permissions
                </flux:button>
            </flux:button.group>

            <!-- ROLES -->
            @if ($activeTab === 'roles')
                <div class="space-y-6">

                    <div class="flex justify-between items-center">
                        <flux:heading size="lg">Roles ({{ $roles->count() }})</flux:heading>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                        <!-- ADD NEW ROLE CARD -->
                        <a href="{{ route('admin.roles.create') }}"
                            class="flex flex-col sm:flex-row items-center gap-3 border-2 border-dashed border-zinc-600/30 rounded-xl p-4 sm:p-5 hover:bg-zinc-700/5 hover:border-zinc-600 transition-all duration-200 text-center sm:text-left">

                            <!-- SVG Icon -->
                            <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-zinc-700/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-zinc-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>

                            <!-- Text -->
                            <span class="text-sm font-semibold text-zinc-300 hover:text-zinc-400 transition-colors">
                                Add New Role
                            </span>
                        </a>

                        <!-- EXISTING ROLES -->
                        @foreach ($roles as $role)
                            <flux:card
                                class="bg-zinc-800 shadow-sm dark:shadow-md border rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">

                                <!-- LEFT: Icon + Name -->
                                <div class="flex flex-row items-center gap-4">
                                    <div
                                        class="w-12 h-12 flex items-center justify-center rounded-lg bg-zinc-700 dark:bg-zinc-800 flex-shrink-0">
                                        <flux:icon.shield-check class="w-6 h-6 text-zinc-400" />
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-semibold text-zinc-200 dark:text-zinc-100">{{ ucfirst($role->name) }}</span>
                                        <span class="text-xs text-zinc-400">{{ $role->users_count ?? 0 }} users</span>
                                    </div>
                                </div>

                                <!-- RIGHT: Edit/Delete Icons -->
                                <div class="flex items-center gap-2 self-end sm:self-auto">
                                    <flux:button icon="pencil" variant="outline" size="sm"
                                        href="{{ route('admin.roles.edit', $role->id) }}" class="min-h-[44px] min-w-[44px] sm:min-h-auto sm:min-w-auto"></flux:button>

                                    @if ($role->name !== 'administrator')
                                        <flux:button icon="trash" variant="danger" size="sm" wire:click="deleteRole({{ $role->id }})" wire:confirm="Are you sure you want to delete this role?" class="min-h-[44px] min-w-[44px] sm:min-h-auto sm:min-w-auto"></flux:button>
                                    @endif
                                </div>

                            </flux:card>
                        @endforeach

                    </div>
                </div>
            @endif

            <!-- PERMISSIONS -->
            <!-- PERMISSIONS -->
@if ($activeTab === 'permissions')
    @php
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return explode('-', $permission->name)[0];
        });
    @endphp

    <div class="space-y-6">

        <!-- Header + Add New Permission Card -->
        <div class="flex justify-between items-center">
            <flux:heading size="lg">Permissions ({{ $permissions->count() }})</flux:heading>
        </div>

        <!-- Permissions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">

            <!-- ADD NEW PERMISSION CARD -->
            <a href="{{ route('admin.permissions.create') }}"
               class="flex flex-col sm:flex-row items-center gap-3 border-2 border-dashed border-zinc-600/30 rounded-xl p-4 sm:p-5 hover:bg-zinc-700/5 hover:border-zinc-600 transition-all duration-200 text-center sm:text-left">
                <!-- Icon -->
                <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-zinc-700/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-zinc-400" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <!-- Text -->
                <span class="text-sm font-semibold text-zinc-300 hover:text-zinc-400 transition-colors">
                    Add New Permission
                </span>
            </a>

            <!-- EXISTING PERMISSIONS -->
            @foreach ($groupedPermissions as $group => $groupItems)
                @foreach ($groupItems as $permission)
                    @php
                        $parts = explode('-', $permission->name);
                        $module = ucfirst($parts[0] ?? 'General');
                        $action = ucfirst($parts[1] ?? '');
                        $label = trim($module . ' ' . $action);
                    @endphp

                    <flux:card
                        class="bg-zinc-800 shadow-md border rounded-xl p-6 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-4 sm:gap-0 hover:shadow-lg transition">

                        <!-- LEFT: Icon + Name -->
                        <div class="flex flex-row items-center gap-3">
                            <div
                                class="w-12 h-12 flex items-center justify-center rounded-lg bg-zinc-700 dark:bg-zinc-800">
                                <flux:icon.shield-check class="w-6 h-6 text-zinc-400" />
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="text-sm font-semibold text-zinc-200 dark:text-zinc-100">{{ $label }}</span>
                            </div>
                        </div>

                        <!-- RIGHT: Edit/Delete -->
                        <div class="flex items-center gap-2 sm:self-auto">
                            <flux:button icon="pencil" variant="outline" size="sm"
                                href="{{ route('admin.permissions.edit', $permission->id) }}" class="min-h-[44px] min-w-[44px] sm:min-h-auto sm:min-w-auto"></flux:button>
                            <flux:button icon="trash" variant="danger" size="sm" wire:click="deletePermission({{ $permission->id }})" wire:confirm="Are you sure you want to delete this permission?" class="min-h-[44px] min-w-[44px] sm:min-h-auto sm:min-w-auto"></flux:button>
                        </div>

                    </flux:card>
                @endforeach
            @endforeach

        </div>
    </div>
@endif

        </div>
    </flux:card>

</div>
