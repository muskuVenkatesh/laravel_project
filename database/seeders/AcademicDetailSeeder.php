<?php

namespace Database\Seeders;

use App\Models\AcademicDetail;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AcademicDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AcademicDetail::create([
            'academic_years' => 2024,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'academic_description' => 'Description for the academic year 2024',
            'status' => 1,
        ]);

        AcademicDetail::create([
            'academic_years' => 2025,
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'academic_description' => 'Description for the academic year 2025',
            'status' => 1,
        ]);
    }
}
