<?php

namespace Database\Seeders;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Models\AdmissionAnouncement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdmissionAnnouncementsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        AdmissionAnouncement::insert([
            [
                'name' => 'Spring Admission 2025',
                'branch_id' => 1,
                'school_id' => 1,
                'academic_year_id' => 2024,
                'application_fee' => 500,
                'start_date' => Carbon::create('2024', '01', '01'),
                'end_date' => Carbon::create('2024', '05', '31'),
                'last_submission_date' => Carbon::create('2024', '06', '15'),
                'class' => 1,
                'admission_fees' => 1000,
                'quota' => 'General',
                'seats_available' => 50,
                'exam_required' => 'yes',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fall Admission 2025',
                'branch_id' => 1,
                'school_id' => 1,
                'academic_year_id' => 2025,
                'application_fee' => 600,
                'start_date' => Carbon::create('2024', '07', '01'),
                'end_date' => Carbon::create('2024', '12', '31'),
                'last_submission_date' => Carbon::create('2025', '01', '15'),
                'class' => 1,
                'admission_fees' => 1200,
                'quota' => 'Reserved',
                'seats_available' => 30,
                'exam_required' =>'yes',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
