<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FeesPayType;

class FeePayTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $feeTypes = [
            [
                'name' => 'Online'
            ],
            [
                'name' => 'Offline'
            ],
            [
                'name' => 'Online & Offline'
            ]
        ];
        FeesPayType::insert($feeTypes);
    }
}
