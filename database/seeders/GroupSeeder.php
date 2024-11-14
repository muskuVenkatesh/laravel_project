<?php

namespace Database\Seeders;

use App\Models\Group;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 10; $i++) {
            Group::create([
                'branch_id' => $faker->numberBetween(1, 5),
                'name' => 'Group ' . $i,
            ]);
        }
    }
}
