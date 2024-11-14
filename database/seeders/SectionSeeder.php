<?php

namespace Database\Seeders;

use App\Models\Section;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 10; $i++) {
            Section::create([
                'class_id' => $faker->numberBetween(1, 5),
                'name' => $faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            ]);
        }
    }
}
