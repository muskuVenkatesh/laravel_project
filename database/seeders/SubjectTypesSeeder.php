<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubjectType;
class SubjectTypesSeeder extends Seeder
{
    public function run()
    {
        $subjectTypes = [
            ['name' => 'Exam'],
            ['name' => 'Homework'],
            ['name' => 'Practical'],
        ];
        SubjectType::insert($subjectTypes);
    }
}
