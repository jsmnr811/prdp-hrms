<div class="space-y-6 p-6">

    <flux:breadcrumbs>
        <flux:breadcrumbs.item>User Management</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.employee-list') }}">Employees</flux:breadcrumbs.item>
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
            <h2 class="text-lg font-semibold dark:text-white">Employees</h2>
            <a href="{{ route('admin.add-employee') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" wire:navigate>
                Add Employee
                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Search --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Search</label>
                <input wire:model.live="search" placeholder="Name, email, or employee number"
                    class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500" />
            </div>

            {{-- Status Filter --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Status</label>
                <select wire:model.live="statusFilter"
                    class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Office Filter --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Office</label>
                <select wire:model.live="officeFilter"
                    class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Offices</option>
                    @foreach ($offices as $office)
                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Unit Filter --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Unit</label>
                <select wire:model.live="unitFilter"
                    class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Units</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Employee Table --}}
    <div class="bg-white dark:bg-zinc-800 shadow-sm dark:shadow-md rounded-lg p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-zinc-600 uppercase bg-zinc-50 dark:bg-zinc-900 dark:text-zinc-400">
                    <tr>
                        <th class="px-2 py-3">Image</th>

                        <th wire:click="sortBy('employee_number')"
                            class="px-2 py-3 cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            Employee #
                            @if ($sortField === 'employee_number')
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th wire:click="sortBy('first_name')"
                            class="px-2 py-3 cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            Name
                            @if ($sortField === 'first_name')
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th class="px-2 py-3">Email</th>
                        <th wire:click="sortBy('employment_status')"
                            class="px-2 py-3 cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            Status
                            @if ($sortField === 'employment_status')
                                @if ($sortDirection === 'asc')
                                    ↑
                                @else
                                    ↓
                                @endif
                            @endif
                        </th>
                        <th class="px-2 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr
                            class="border-b dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                            <td class="px-2 py-3">
                                <flux:avatar src="{{ $employee->image ? asset('storage/' . $employee->image) : null }}"
                                    name="{{ $employee->full_name }}" size="sm" />
                            </td>
                            <td class="px-2 py-3 font-medium text-zinc-900 dark:text-white">
                                {{ $employee->formatted_employee_number }}
                            </td>

                            <td class="px-2 py-3 text-zinc-900 dark:text-white">{{ $employee->full_name }}</td>
                            <td class="px-2 py-3 text-zinc-700 dark:text-zinc-300">{{ $employee->email }}</td>
                            <td class="px-2 py-3">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($employee->employment_status === 'Hired') bg-green-100 text-green-800
                                    @elseif($employee->employment_status === 'Resigned') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $employee->employment_status }}
                                </span>
                            </td>
                            <td class="px-2 py-3 relative">
                                @if ($employee->user)
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
                                                <button wire:click="editEmployee({{ $employee->id }})"
                                                    class="block w-full text-left px-2 py-2 text-sm text-zinc-700 dark:text-zinc-100 hover:bg-gray-100 dark:hover:bg-zinc-600">
                                                    Edit Employee
                                                </button>
                                                {{-- <button wire:click="confirmResendWelcomeEmail({{ $employee->employee_number }})"
                                                    class="block w-full text-left px-2 py-2 text-sm text-zinc-700 dark:text-zinc-100 hover:bg-gray-100 dark:hover:bg-zinc-600">
                                                    Resend Email
                                                </button> --}}
                                                <button
                                                    wire:click="confirmResetPassword({{ $employee->employee_number }})"
                                                    class="block w-full text-left px-2 py-2 text-sm text-zinc-700 dark:text-zinc-100 hover:bg-gray-100 dark:hover:bg-zinc-600">
                                                    Reset Password
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-zinc-500 dark:text-zinc-400">No User</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-2 py-8 text-center text-zinc-600 dark:text-zinc-400">
                                No employees found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $employees->links() }}
        </div>
    </div>
</div>
