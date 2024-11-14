<?php

namespace Database\Seeders;

use App\Models\Subjects;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjectIds = Subjects::pluck('id');
        foreach ($subjectIds as $subject_id) {
            $subjects[] = [
                'branch_id' => 1,
                'class_id' => 1,
                'section_id' => 1,
                'subject_id' => $subject_id,
                'subject_label' => 1,
                'subject_type' => 1,
                'subject_code' => 'SSCQ'

            ];
        }
        DB::table('branch_subjects')->insert($subjects);
    }
}
