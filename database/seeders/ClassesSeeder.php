<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classes;
use Faker\Factory as Faker;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $numRecords = 10;

        foreach (range(1, $numRecords) as $index) {
            Classes::create([
                'branch_id' => 1,
                'name' => $faker->word
            ]);
        }
    }
}
