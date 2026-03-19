<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PositionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('positions')->delete();
        
        \DB::table('positions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Project Director',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Deputy Project Director',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Component Head',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Alternate Component Head',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Unit Head',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Alternate Unit Head',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Legal Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Senior Planning Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Senior Rural Infrastructure Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Senior Project Development Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Senior Institutional Development Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Senior Economist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Project Accountant',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Rural Infrastructure Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Planning Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Business Development Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Enterprise Development & Marketing Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Organizational Development Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Monitoring and Evaluation Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            19 => 
            array (
                'id' => 20,
            'name' => 'Management Information System Specialist (Output-Based)',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Knowledge Management Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Social Safeguards Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Environmental Safeguards Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'Procurement Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'GIS Data Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'Information Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'Institutional Development Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'Finance Management Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'Financial Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'Financial Analyst III',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'Budget Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'Cashier',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'Compliance Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'Economist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'Rural Infrastructure Engineer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'Planning Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'Project Development Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'MIS Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'Monitoring and Evaluation Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'Knowledge Management Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'GIS Data Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'Procurement Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'SES Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'GRM Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'Human Resource Management Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'Information Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'Institutional Development Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'Budget Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'Media Production Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'Financial Management Associate',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            50 => 
            array (
                'id' => 51,
                'name' => 'Financial Analyst I',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'Budget Analyst',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'Rural Infrastructure Associate',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'Enterprise Development Associate',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            54 => 
            array (
                'id' => 55,
                'name' => 'Associate Economist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            55 => 
            array (
                'id' => 56,
                'name' => 'Media Production Associate',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            56 => 
            array (
                'id' => 57,
                'name' => 'Associate Procurement Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            57 => 
            array (
                'id' => 58,
                'name' => 'Associate M & E Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            58 => 
            array (
                'id' => 59,
                'name' => 'Associate SES Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            59 => 
            array (
                'id' => 60,
                'name' => 'Financial Management Assistant',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            60 => 
            array (
                'id' => 61,
                'name' => 'Legal Assistant',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            61 => 
            array (
                'id' => 62,
                'name' => 'Administrative Officer III',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            62 => 
            array (
                'id' => 63,
                'name' => 'Supply and Property Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            63 => 
            array (
                'id' => 64,
                'name' => 'Project Development Associate',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            64 => 
            array (
                'id' => 65,
                'name' => 'Photographer/Videographer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            65 => 
            array (
                'id' => 66,
                'name' => 'Administrative Officer II',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            66 => 
            array (
                'id' => 67,
                'name' => 'Administrative Officer I',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            67 => 
            array (
                'id' => 68,
                'name' => 'Associate Supply & Property Officer',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            68 => 
            array (
                'id' => 69,
                'name' => 'Administrative Assistant',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            69 => 
            array (
                'id' => 70,
                'name' => 'HRMA',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            70 => 
            array (
                'id' => 71,
                'name' => 'Cash Clerk',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            71 => 
            array (
                'id' => 72,
                'name' => 'Driver/Mechanic',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            72 => 
            array (
                'id' => 73,
                'name' => 'Driver',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            73 => 
            array (
                'id' => 74,
                'name' => 'Administrative Aide',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
            74 => 
            array (
                'id' => 75,
                'name' => 'SES Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id' => 76,
                'name' => 'Planning Specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id' => 77,
            'name' => 'GIS Specialist (Programmer)',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id' => 78,
                'name' => 'GIS specialist',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id' => 79,
            'name' => 'GIS officer (Programmer)',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id' => 80,
                'name' => 'Financial Analyst II',
                'office_id' => NULL,
                'component_id' => NULL,
                'unit_id' => NULL,
                'created_at' => '2025-11-12 13:14:36',
                'updated_at' => '2025-11-12 13:14:36',
            ),
        ));
        
        
    }
}