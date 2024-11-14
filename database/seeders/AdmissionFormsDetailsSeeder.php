<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class AdmissionFormsDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Generate dummy data
        foreach (range(1, 10) as $index) {
            DB::table('admission_forms_details')->insert([
                'admission_id' => 1,
                'father_name' => $faker->name,
                'middle_name' => $faker->firstName,
                'father_last_name' => $faker->lastName,
                'phone' => $faker->phoneNumber,
                'father_phone' => $faker->phoneNumber,
                'father_email' => $faker->email,
                'father_education' => $faker->word,
                'father_occupation' => $faker->word,
                'annual_income' => $faker->randomFloat(2, 10000, 1000000),
                'father_aadhaar_no' => $faker->word,
                'father_pan_card' => $faker->word,
                'mother_name' => $faker->name,
                'mother_phone' => $faker->phoneNumber,
                'mother_email' => $faker->email,
                'mother_education' => $faker->word,
                'mother_occupation' => $faker->word,
                'mother_annual_income' => $faker->randomFloat(2, 10000, 1000000),
                'mother_aadhaar_no' => $faker->word,
                'mother_pan_card' => $faker->word,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
