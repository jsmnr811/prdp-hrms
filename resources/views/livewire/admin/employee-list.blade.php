<div class="space-y-6 p-6">

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
        <h2 class="text-lg font-semibold mb-4 dark:text-white">Employee List</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Search --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Search</label>
                <input wire:model.live="search" placeholder="Name, email, or employee number" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500" />
            </div>

            {{-- Status Filter --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Status</label>
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Office Filter --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Office</label>
                <select wire:model.live="officeFilter" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Offices</option>
                    @foreach($offices as $office)
                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Unit Filter --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Unit</label>
                <select wire:model.live="unitFilter" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Units</option>
                    @foreach($units as $unit)
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
                        <th wire:click="sortBy('employee_number')" class="px-4 py-3 cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            Employee #
                            @if($sortField === 'employee_number')
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            @endif
                        </th>
                        <th wire:click="sortBy('first_name')" class="px-4 py-3 cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            Name
                            @if($sortField === 'first_name')
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            @endif
                        </th>
                        <th class="px-4 py-3">Email</th>
                        <th wire:click="sortBy('employment_status')" class="px-4 py-3 cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            Status
                            @if($sortField === 'employment_status')
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            @endif
                        </th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr class="border-b dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                                {{ $employee->formatted_employee_number }}
                            </td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ $employee->full_name }}</td>
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">{{ $employee->email }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($employee->employment_status === 'Hired') bg-green-100 text-green-800
                                    @elseif($employee->employment_status === 'Resigned') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ $employee->employment_status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($employee->user)
                                    <button class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600" wire:click="resendWelcomeEmail({{ $employee->user->id }})" wire:confirm="Resend welcome email to {{ $employee->full_name }}?">
                                        Resend Email
                                    </button>
                                     <button class="px-3 py-1 text-sm bg-yellow-500 text-white rounded hover:bg-yellow-600 ml-2" wire:click="openResetPasswordModal({{ $employee->id }})">
                                         Reset Password
                                     </button>
                                @else
                                    <span class="text-zinc-500 dark:text-zinc-400">No User</span>
                                @endif
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

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $employees->links() }}
        </div>
    </div>
     {{-- Reset Password Modal --}}
     @if($showResetPasswordModal)
         <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:ignore.self>
             <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-800">
                 <div class="mt-3 text-center">
                     <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Reset Password</h3>
                     <div class="mt-2 px-7 py-3">
                         <p class="text-sm text-gray-600 dark:text-zinc-400">Are you sure you want to reset this employee's password to the default format?</p>
                         <div class="flex items-center px-4 py-3 space-x-2">
                             <button type="button" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md flex-1 shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300" wire:click="resetPassword">
                                 Reset Password
                             </button>
                             <button type="button" class="px-4 py-2 bg-gray-300 dark:bg-zinc-600 text-gray-900 dark:text-zinc-100 text-base font-medium rounded-md flex-1 shadow-sm hover:bg-gray-400 dark:hover:bg-zinc-500 focus:outline-none focus:ring-2 focus:ring-gray-300" wire:click="$set('showResetPasswordModal', false)">
                                 Cancel
                             </button>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     @endif
</div>