<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserDetails;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run($parent_id=null): void
    {

        $faker = Faker::create();

        $user = User::create([
            'name' => $faker->firstName,
            'email' => $faker->email,
            'roleid' => 7,
            'phone' => $faker->phoneNumber,
            'status' => 1,
            'password' =>  Hash::make('password'),
          ]);

          $studentData =  Student::create([
            'user_id' => $user->id,
            'branch_id' => 1,
            'parent_id' => $parent_id,
            'academic_year_id' => 1,
            'roll_no' => $faker->unique()->numberBetween(1000, 9999),
            'first_name' => $faker->firstName,
            'middle_name' => $faker->optional()->firstName,
            'last_name' => $faker->lastName,
            'fee_book_no' => $faker->unique()->numberBetween(1000, 9999),
            'place_of_birth' => $faker->city,
            'mother_tongue' => rand(1, 10),
            'physically_challenge' => $faker->boolean,
            'neet_applicable' => $faker->boolean,
            'transport_required' => $faker->boolean,
            'medium_id' => $faker->numberBetween(1, 5),
            'class_id' => $faker->numberBetween(1, 10),
            'section_id' => $faker->numberBetween(1, 5),
            'group_id' => $faker->numberBetween(1, 5),
            'reg_no' => $faker->unique()->numberBetween(1000, 9999),
            'emis_no' => $faker->optional()->numberBetween(1000, 9999),
            'cse_no' => $faker->optional()->numberBetween(1000, 9999),
            'file_no' => $faker->unique()->numberBetween(1000, 9999),
            'admission_no' => $faker->unique()->numberBetween(1000, 9999),
            'application_no' => $faker->unique()->numberBetween(1000, 9999),
            'admission_date' => date('Y-m-d'),
            'joining_quota' => $faker->word,
            'first_lang_id' => $faker->numberBetween(1, 5),
            'second_lang_id' => $faker->numberBetween(1, 5),
            'third_lang_id' => $faker->numberBetween(1, 5),
            'achievements' => $faker->sentence,
            'area_of_interest' => $faker->word,
            'additional_skills' => $faker->word,
            'image' => $faker->imageUrl()
        ]);

        UserDetails::create([
            'user_id' => $studentData->id,
            'branch_id' => 1,
        ]);
    }
}
