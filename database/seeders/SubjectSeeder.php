<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subjects;
class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $subjects = [
            [
                'name' => 'History',
            ],
            [
                'name' => 'Geography',
            ],
            [
                'name' => 'Political Science',
            ],
            [
                'name' => 'Psychology',
            ],
            [
                'name' => 'Sociology',
            ],
            [
                'name' => 'Fine Arts',
            ]
        ];

        Subjects::insert($subjects);
    }

}
