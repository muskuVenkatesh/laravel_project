<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcademicSchoolSetup;

class AcademicSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run($branchId,$schoolId): void
    {
        AcademicSchoolSetup::create([
            'academic_id' => 1,
            'school_id' => $branchId,
            'branch_id' => $schoolId,
        ]);
    }
}
