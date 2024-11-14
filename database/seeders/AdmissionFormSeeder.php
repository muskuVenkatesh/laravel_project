<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use App\Models\AdmissionForms;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdmissionFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'branch_id' => 1,
                'announcement_id' => 1,
                'application_type' => 'Type ' . ($i + 1),
                'academic_year_id' => 1,
                'first_name' => 'FirstName ' . ($i + 1),
                'middle_name' => 'MiddleName ' . ($i + 1),
                'last_name' => 'LastName ' . ($i + 1),
                'fee_book_no' => 1000 + $i,
                'place_of_birth' => 'City ' . ($i + 1),
                'mother_tongue' => 'Language ' . ($i + 1),
                'physically_challenge' => ($i % 2 == 0) ? 'Yes' : 'No',
                'neet_applicable' => 'no',
                'transport_required' => 'no',
                'class_id' => 1,
                'reg_no' => 1000 + $i,
                'emis_no' => 2000 + $i,
                'cse_no' => 3000 + $i,
                'file_no' => 4000 + $i,
                'admission_no' => 5000 + $i,
                'admission_fee' => 200.00 + ($i * 10),
                'admission_status' => 'Status ' . ($i + 1),
                'application_no' => 6000 + $i,
                'application_fee' => 100.00 + ($i * 5),
                'application_status' => 'Status ' . ($i + 1),
                'admission_date' => now()->subDays($i)->format('Y-m-d'),
                'joining_quota' => 'Quota ' . ($i + 1),
                'first_lang_id' => 1,
                'second_lang_id' => 2,
                'third_lang_id' => 3,
                'achievements' => 'Achievement ' . ($i + 1),
                'area_of_interest' => 'Interest ' . ($i + 1),
                'additional_skills' => 'Skills ' . ($i + 1),
                'previous_school' => 'School ' . ($i + 1),
                'last_study_course' => 'Course ' . ($i + 1),
                'last_exam_marks' => 50 + ($i % 51),
                'reason_change' => 'Reason ' . ($i + 1),
                'reason_gap' => 'Gap Reason ' . ($i + 1),
                'date_of_birth' => now()->subYears(15 + $i)->format('Y-m-d'),
                'gender' => ($i % 2 == 0) ? 'Male' : 'Female',
                'blood_group' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'][($i % 8)],
                'religion' => 'Religion ' . ($i + 1),
                'cast' => 'Cast ' . ($i + 1),
                'nationality' => 'Country ' . ($i + 1),
                'mother_tongue' => 'Language ' . ($i + 1),
                'addhar_card_no' => '1234 5678 9012',
                'pan_card_no' => 'A12 B34 C56 D',
                'address' => 'Address ' . ($i + 1),
                'city' => 'City ' . ($i + 1),
                'state' => 'State ' . ($i + 1),
                'country' => 'Country ' . ($i + 1),
                'pin' => 505460,
                'payment_status' => 'unpaid',
                'school_enquiry' => 'Enquiry ' . ($i + 1),
                'hostel_required' => 'no',
                'identification_mark' => 'Mark ' . ($i + 1),
                'identification_mark_two' => 'Mark Two ' . ($i + 1),
                'sports' => 'Sport ' . ($i + 1),
                'volunteer' => 'Volunteer ' . ($i + 1),
                'quota' => 'Quota ' . ($i + 1),
            ];
        }
        AdmissionForms::insert($data);
    }


}
