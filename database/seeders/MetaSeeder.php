<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branchmeta;
use Faker\Factory as Faker;


class MetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run($branchId): void
    {
        $faker = Faker::create();

        $branchMetaAttributes = [
            [
                'branch_id' => $branchId,
                'name' => 'report_card',
                'value' => $faker->image,
            ],
            [
                'branch_id' => $branchId,
                'name' => 'logo_file',
                'value' => $faker->image,
            ],
            [
                'branch_id' => $branchId,
                'name' => 'text_logo',
                'value' => $faker->image,
            ],
            [
                'branch_id' => $branchId,
                'name' => 'print_file',
                'value' => $faker->image,
            ],
        ];

        foreach ($branchMetaAttributes as $attributes) {
            Branchmeta::create($attributes);
        }
    }
}
