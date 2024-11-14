<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FeesDiscountType;

class FeeDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $feeTypes = [
            [
                'name' => 'Girls Discount',
                'amount' => 450
            ],
            [
                'name' => 'Government Discount',
                'amount' => 360
            ],
            [
                'name' => 'Special Student Discount',
                'amount' => 200
            ],
            [
                'name' => 'Request Discount',
                'amount' => 100
            ]
        ];

        FeesDiscountType::insert($feeTypes);
    }
}
