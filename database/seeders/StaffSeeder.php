<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Staff;
use App\Models\UserDetails;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $user = User::create([
            'name' => $faker->firstName,
            'email' => $faker->email,
            'roleid' => 4,
            'phone' => $faker->phoneNumber,
            'username' => $faker->userName,
            'status' => 1,
            'password' =>  Hash::make('password'),
          ]);

        // for ($i = 1; $i <= 10; $i++) {
            $staff = Staff::create([
                'branch_id' => 1,
                'user_id' => $user->id,
                'employee_no' => $faker->unique()->numerify('EMP###'),
                'first_name' => $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'epf_no' => $faker->numerify('EPF######'),
                'uan_no' => $faker->numerify('UAN######'),
                'esi_no' => $faker->numerify('ESI######'),
                'marital_status' => 'married',
                'anniversary_date' => $faker->optional()->date('Y-m-d'),
                'spouse_name' => $faker->optional()->name,
                'kid_studying' =>'yes', // Ensure this value is acceptable based on the check constraint
                'assigned_activity' => 'Activity ' . $faker->numberBetween(1, 10),
                'joining_date' => $faker->date('Y-m-d'),
                'specialized' => $faker->numberBetween(1, 10),
                'department' => $faker->numberBetween(1, 10),
                'work_location' => $faker->city,
                'qualification' => $faker->numberBetween(1, 10),
                'extra_qualification' => $faker->numberBetween(1, 10),
                'previous_school' => $faker->company,
                'reason_change' => $faker->sentence,
            ]);


            UserDetails::create([
                'user_id' => $user->id,
                'branch_id' => 1,
            ]);
        // }
    }
}
