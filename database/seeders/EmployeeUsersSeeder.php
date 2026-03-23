<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\Employee;
use App\Models\User;

class EmployeeUsersSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {

            // Special case for admin
            if ($employee->employee_number === '0000') {
                $username = 'admin';
                $password = 'password';
            } else {
                $firstInitial = strtoupper(substr($employee->first_name, 0, 1));
                $lastInitial = strtolower(substr($employee->last_name, 0, 1));
                $formattedLastName = strtolower($employee->last_name);
                $employeeNumber = $employee->employee_number;

                $username = $firstInitial . $lastInitial . $employeeNumber;

                // Password: first initial + last name + employee_number
                $password = $firstInitial . $formattedLastName . $employeeNumber;
            }

            User::updateOrCreate(
                ['employee_number' => $employee->employee_number],
                [
                    'employee_id' => $employee->id,
                    'username' => $username,
                    'password' => Hash::make($password),
                    'status' => 1,
                    'email_verified_at' => now(),
                    'must_change_password' => 0,
                ]
            );
        }

        $this->command->info("All users created for existing employees.");
    }
}
