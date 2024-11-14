<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransportRoute;
use Faker\Factory as Faker;

class TransportRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            TransportRoute::create([
                'branch_id' => $faker->numberBetween(1, 5), 
                'start_point' => $faker->city,
                'end_point' => $faker->city,
                'start_latitude' => $faker->latitude,
                'start_logitude' => $faker->longitude,
                'end_latitude' => $faker->latitude,
                'end_logitude' => $faker->longitude,
                'distance' => $faker->randomFloat(2, 5, 50), 
                'status' => '1',
            ]);
        }
      }
}
