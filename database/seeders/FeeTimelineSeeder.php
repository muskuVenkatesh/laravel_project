<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FeesTimeline;

class FeeTimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            //
            $feeTypes = [
                [
                    'name' => 'Quarterly',
                    'installments' => '2'
                ],
                [
                    'name' => 'Annual',
                    'installments' => '1'
                ],
                [
                    'name' => 'Monthly',
                    'installments' => '12'
                ]
            ];
            FeesTimeline::insert($feeTypes);
    }
}
