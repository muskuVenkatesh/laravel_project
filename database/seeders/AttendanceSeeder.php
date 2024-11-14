<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
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
            Attendance::create([
                'branch_id' => $faker->numberBetween(1, 10),
                'class_id' => $faker->numberBetween(1, 5),
                'section_id' => $faker->numberBetween(1, 5),
                'subject_id' => $faker->numberBetween(1, 10),
                'present_student_id' => json_encode($faker->randomElements([1, 2, 3, 4, 5], rand(2, 4))),
                'absent_student_id' => json_encode($faker->randomElements([6, 7, 8, 9], rand(1, 2))),
                'attendance_date' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d')
            ]);
        }
    }
}
