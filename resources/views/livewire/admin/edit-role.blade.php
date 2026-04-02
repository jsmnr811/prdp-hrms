<div x-data class="space-y-6 sm:space-y-8 p-4 sm:p-6 lg:p-8">

    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.role-permission-management') }}">Role & Permission Management</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit Role</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
        <flux:icon.user-group class="h-8 w-8 text-zinc-300 flex-shrink-0" />
        <flux:heading size="xl">Edit Role</flux:heading>
    </div>

    <!-- Form Card -->
    <flux:card class="bg-zinc-800 shadow-sm dark:shadow-md">
    <div class="p-6 sm:p-8">

            <form wire:submit.prevent="confirmSaveRole" class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Role Name -->
                <flux:field class="col-span-1 lg:col-span-2">
                    <flux:label icon="tag">Role Name</flux:label>
                    <flux:input
                        wire:model="roleName"
                        placeholder="Enter a descriptive role name"
                        class="w-full rounded-lg border-zinc-600 dark:border-zinc-700 dark:bg-zinc-800 focus:ring-2 focus:ring-zinc-500 transition-all duration-200"
                    />
                    @error('roleName') <flux:error>{{ $message }}</flux:error> @enderror
                </flux:field>

                <!-- Permissions -->
                <flux:field class="col-span-1 lg:col-span-2">
                    <flux:label icon="shield-check">Permissions</flux:label>
                    <flux:checkbox.group variant="cards" wire:model="selectedPermissions" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                        @foreach($permissions as $permission)
                            <flux:checkbox value="{{ $permission->id }}" class="flex items-center justify-center p-3 border rounded-lg cursor-pointer hover:scale-105 transition-transform duration-200 text-center dark:border-zinc-600 dark:bg-zinc-800">
                                {{ $permission->name }}
                            </flux:checkbox>
                        @endforeach
                    </flux:checkbox.group>
                    <flux:description>Select the permissions to assign to this role</flux:description>
                </flux:field>

                <!-- Form Actions -->
                <div class="col-span-1 lg:col-span-2 flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 mt-4 border-t border-zinc-600 dark:border-zinc-700 pt-4">
                    <flux:button
                        icon="arrow-left"
                        variant="ghost"
                        href="{{ route('admin.role-permission-management') }}"
                        class="rounded-lg hover:bg-zinc-700 dark:hover:bg-zinc-800 transition min-h-[44px] w-full sm:w-auto sm:min-h-auto"
                    >
                        Cancel
                    </flux:button>
                    <flux:button
                        icon="check"
                        type="submit"
                        variant="primary"
                        wire:loading.attr="disabled"
                        class="rounded-lg hover:scale-105 transition-transform duration-200 min-h-[44px] w-full sm:w-auto sm:min-h-auto"
                    >
                        <span wire:loading.remove>Update Role</span>
                        <span wire:loading>Updating...</span>
                    </flux:button>
                </div>

            </form>
        </div>
    </flux:card>

</div>
