<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
          $this->call(ComponentsTableSeeder::class);
        $this->call(OfficesTableSeeder::class);
        $this->call(PositionsTableSeeder::class);
        $this->call(UnitsTableSeeder::class);
                $this->call(UsersTableSeeder::class);
        $this->call(EmployeesTableSeeder::class);
        $this->call([
            AdminSeeder::class,      // Create administrator first
            EmployeeRoleSeeder::class,   // Create other employees
        ]);

    }
}
