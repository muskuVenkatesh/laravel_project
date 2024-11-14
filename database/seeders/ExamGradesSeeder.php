<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExamGrades;

class ExamGradesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ExamGrades::insert([
            [
                'branch_id' => 1,
                'class_id' => 1,
                'max_marks' => 35,
                'min_marks' => 0,
                'name' => 'c',
                'status' => 1,
            ],
            [
                'branch_id' => 1,
                'class_id' => 1,
                'max_marks' => 50,
                'min_marks' => 35,
                'name' => 'B',
                'status' => 1,
            ],
            [
                'branch_id' => 1,
                'class_id' => 1,
                'max_marks' => 65,
                'min_marks' => 50,
                'name' => 'A',
                'status' => 1,
            ],
            [
                'branch_id' => 1,
                'class_id' => 1,
                'max_marks' => 100,
                'min_marks' => 65,
                'name' => 'A+',
                'status' => 1,
            ],
        ]);
    }
}
