<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('units')->delete();
        
        \DB::table('units')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'ACCOUNTING',
                'name' => 'Accounting Unit',
                'office_id' => 6,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'FINANCE',
                'name' => 'Finance  Unit',
                'office_id' => 6,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'PROCUREMENT',
                'name' => 'Procurement  Unit',
                'office_id' => 6,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'BUDGET',
                'name' => 'Budget  Unit',
                'office_id' => 6,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'ADMIN',
                'name' => 'Administrative Unit',
                'office_id' => 6,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'M&E',
                'name' => 'Monitoring and Evaluation  Unit',
                'office_id' => 6,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            6 => 
            array (
                'id' => 7,
                'code' => 'SES',
                'name' => 'Social and Environmental Safeguards Unit',
                'office_id' => 6,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            7 => 
            array (
                'id' => 8,
                'code' => 'GGU',
                'name' => 'Geomapping and Governance Unit',
                'office_id' => 6,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            8 => 
            array (
                'id' => 9,
                'code' => 'INFOACE',
                'name' => 'Information, Advocacy, Communication and Education Unit',
                'office_id' => 6,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            9 => 
            array (
                'id' => 10,
                'code' => 'IDU',
                'name' => 'Institutional Development Unit',
                'office_id' => 6,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            10 => 
            array (
                'id' => 11,
                'code' => 'ECONOMICS',
                'name' => 'Economics Unit',
                'office_id' => 6,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
        ));
        
        
    }
}