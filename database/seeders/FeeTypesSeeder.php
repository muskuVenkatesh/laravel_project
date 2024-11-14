<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FeesType;

class FeeTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $feeTypes = [
            [
                'name' => 'Academic Fee'
            ],
            [
                'name' => 'Transportation Fee'
            ],
            [
                'name' => 'Hostel Fee'
            ],
            [
                'name' => 'Others Fee'
            ],
            [
                'name' => 'ID Card Re-Print'
            ],
            [
                'name' => 'library fees'
            ]
        ];

        FeesType::insert($feeTypes);
    }
}
