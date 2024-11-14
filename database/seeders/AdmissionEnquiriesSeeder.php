<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\AdmissionEnquiry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdmissionEnquiriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            [
                'announcement_id' => 1,
                'application_no' => 3374,
                'application_fee' => 121.76,
                'name' => 'Jaeden Donnelly',
                'father_name' => 'Angelina Schultz',
                'contact_no' => '1-580-957-8098',
                'email' => 'stevie96@example.com',
                'class_applied' => 1,
                'dob' => '1996-04-04',
                'assesment_date' => '2024-01-01',
                'second_language' => 1,
                'course_type' => 1,
                'payment_mode' => 'online',
                'admission_status' => 'pending',
                'status' => 1
            ],
        ];
        AdmissionEnquiry::insert($data);
    }
}
