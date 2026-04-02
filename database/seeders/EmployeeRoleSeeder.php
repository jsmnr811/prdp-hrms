<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class EmployeeRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles if they don't exist
        $employeeRole = Role::firstOrCreate(['name' => 'Employee']);
        $adminRole = Role::firstOrCreate(['name' => 'Administrator']);

        // Assign Administrator role to employee_number 0000
        $adminUser = User::where('employee_number', '0000')->first();
        if ($adminUser && !$adminUser->hasRole('Administrator')) {
            $adminUser->assignRole($adminRole);
            $this->command->info("Assigned Administrator role to: {$adminUser->username} (Employee #: 0000)");
        }

        // Assign Employee role to all other users without roles
        $usersWithoutRoles = User::doesntHave('roles')
            ->where('employee_number', '!=', '0000')
            ->get();

        foreach ($usersWithoutRoles as $user) {
            $user->assignRole($employeeRole);
            $this->command->info(
                "Assigned Employee role to: {$user->username} (Employee #: {$user->employee_number})"
            );
        }

        $this->command->info('Roles assigned successfully.');
    }
}
