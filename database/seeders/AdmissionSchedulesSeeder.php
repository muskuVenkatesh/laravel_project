<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AdmissionSchedulesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 10) as $index) {
            DB::table('admission_schedules')->insert([
                'enquiry_id' => $faker->numberBetween(1, 100),
                'venue' => $faker->address,
                'interview_date' => $faker->date(),
                'comments' => $faker->sentence,
                'schedule_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

