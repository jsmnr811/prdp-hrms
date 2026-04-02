<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('office_categories')->insert([
            ['name' => 'NPCO'],
            ['name' => 'PSO'],
            ['name' => 'RPCO'],
        ]);
    }
}
