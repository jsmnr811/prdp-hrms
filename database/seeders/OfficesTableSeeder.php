<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfficesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('offices')->delete();
        
        \DB::table('offices')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'ONPD',
                'name' => 'Office of the National Project Director',
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'ONDPD',
                'name' => 'Office of the National Deputy Project Director',
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'I-BUILD',
                'name' => 'Intensified Building Up of Infrastructure and Logistics for Development',
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'I-REAP',
                'name' => 'Investments for Rural Enterprises and Agricultural and Fisheries Productivity',
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'I-PLAN',
                'name' => 'Investments for AFMP Planning at the Local and National Levels',
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'I-SUPPORT',
                'name' => 'Implementation Support to PRDP',
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
        ));
        
        
    }
}