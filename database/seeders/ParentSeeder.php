<?php

namespace Database\Seeders;

use App\Models\Parents;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Database\Seeder;
use Database\Seeders\StudentSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 5) as $index) {
            $user = User::create([
                'name' => $faker->firstName,
                'email' => $faker->email,
                'roleid' => 6,
                'phone' => $faker->phoneNumber,
                'status' => 1,
                'password' =>  Hash::make('password'),
              ]);
              $parent = Parents::create([
                'user_id' => $user->id,
                'parent_uid' => Str::uuid(),
                'branch_id' => 1,
                'first_name' => $faker->firstName,
                'middle_name' => null,
                'last_name' => $faker->lastName,
                'phone' => $user->phone,
                'alt_phone' => $faker->phoneNumber,
                'alt_email' => $user->email,
                'education' => 1,
                'occupation' => 1,
                'annual_income' => '84280',
                'mother_name' => $faker->firstName,
                'mother_phone' => $faker->phoneNumber,
                'mother_email' => $faker->email,
                'mother_education' => 1,
                'mother_occupation' => 1,
                'mother_annual_income' => '88861',
                'mother_aadhaar_no' => '227631224203',
                'mother_pan_card' => 'HVJFG2550L',
                'mother_dob' => $faker->date($format = 'Y-m-d', $max = 'now'),
            ]);
            UserDetails::create([
                'user_id' => $user->id,
                'branch_id' => 1,
            ]);
            //print_r($parent->id); die;
            $studentSeeder = new StudentSeeder();
            $studentSeeder->run($parent->id);
        }
    }


}
