<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransportRouteStop;
use App\Models\TransportRoute;
use Faker\Factory as Faker;

class TransportRouteStopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $routeIds = TransportRoute::pluck('id')->toArray();

        if (empty($routeIds)) {
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            TransportRouteStop::create([
                'route_id' => $faker->randomElement($routeIds),
                'stop_data' => [
                    [
                        'stop_name' => $faker->city,
                        'latitude' => $faker->latitude,
                        'longitude' => $faker->longitude,
                        'distance' => $faker->randomFloat(2, 0.5, 20),
                        'amount' => $faker->randomFloat(2, 10, 100),
                    ],
                    [
                        'stop_name' => $faker->city,
                        'latitude' => $faker->latitude,
                        'longitude' => $faker->longitude,
                        'distance' => $faker->randomFloat(2, 0.5, 20),
                        'amount' => $faker->randomFloat(2, 10, 100),
                    ],
                    [
                        'stop_name' => $faker->city,
                        'latitude' => $faker->latitude,
                        'longitude' => $faker->longitude,
                        'distance' => $faker->randomFloat(2, 0.5, 20),
                        'amount' => $faker->randomFloat(2, 10, 100),
                    ],
                ],
                'status' => '1',
            ]);
        }
    }
}
