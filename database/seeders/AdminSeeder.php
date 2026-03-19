<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create Administrator Role with all permissions
        $adminRole = Role::firstOrCreate(
            ['name' => 'administrator'],
            ['guard_name' => 'web']
        );

        // Create Employee Role with limited permissions
        $employeeRole = Role::firstOrCreate(
            ['name' => 'employee'],
            ['guard_name' => 'web']
        );

        // Create all permissions
        $permissions = [
            // Dashboard
            'view dashboard',

            // Employee management
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            'export employees',

            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Role & Permission management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'assign permissions',

            // Office/Unit/Position management
            'view offices',
            'create offices',
            'edit offices',
            'delete offices',
            'view units',
            'create units',
            'edit units',
            'delete units',
            'view positions',
            'create positions',
            'edit positions',
            'delete positions',

            // Reports
            'view reports',
            'generate reports',
            'export reports',

            // System settings
            'view settings',
            'edit settings',

            // Profile
            'view profile',
            'edit profile',
            'change password',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );
        }

        // Assign all permissions to administrator
        $adminRole->syncPermissions(Permission::all());

        // Assign limited permissions to employee
        $employeeRole->syncPermissions([
            'view dashboard',
            'view employees',
            'view profile',
            'edit profile',
            'change password',
        ]);

        // Create Administrator Employee (0000)
        $adminEmployee = Employee::firstOrCreate(
            ['employee_number' => '0000'],
            [
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'middle_name' => null,
                'middle_initial' => null,
                'suffix' => null,
                'contact_number' => null,
                'email' => 'admin@prdp.gov.ph',
                'gender' => null,
                'birth_date' => null,
                'tin' => null,
                'blood_type' => null,
                'landbank_account' => null,
                'height' => null,
                'weight' => null,
                'address' => null,
                'emergency_contact_name' => null,
                'emergency_contact_relationship' => null,
                'emergency_contact_number' => null,
                'image' => null,
                'terms' => 1,
                'office_id' => 6,
                'unit_id' => 8,
                'position_id' => 79,
                'employment_status' => 'Hired',
                'date_hired' => now(),
                'date_ended' => null,
            ]
        );

        // Create Administrator User
        $adminUser = User::firstOrCreate(
            ['employee_number' => '0000'],
            [
                'username' => 'administrator',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Assign administrator role
        $adminUser->assignRole('administrator');

        $this->command->info('Administrator created:');
        $this->command->info('Employee #: 0000');
        $this->command->info('Username: administrator');
        $this->command->info('Password: password');
    }
}
