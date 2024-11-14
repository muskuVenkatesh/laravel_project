<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SchoolBrancheSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SchoolSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('school_branche_settings')->insert([

                'school_id' => 1,
                'branch_id' => 1,
                'stud_grade' => 'A',
                'reg_start_from' => 1000,
                'reg_prefix_digit' => 4,
                'offline_payments' => '1',
                'fees_due_days' => 30,
                'cal_fees_fine' => '0',
                'status' => '1',
                'deleted_at' => null,
        ]);
    }
}
