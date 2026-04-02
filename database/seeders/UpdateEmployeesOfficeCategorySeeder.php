<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateEmployeesOfficeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $npco = DB::table('office_categories')->where('name', 'NPCO')->first();

        if ($npco) {
            DB::table('employees')
                ->whereNull('office_category_id')
                ->update(['office_category_id' => $npco->id]);
        }
    }
}
