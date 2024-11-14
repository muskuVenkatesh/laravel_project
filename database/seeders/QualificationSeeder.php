<?php

namespace Database\Seeders;

use App\Models\Qualifications;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Qualifications::create([
            'name' => 'High School Diploma',
            'status' => 1,
        ]);

        Qualifications::create([
            'name' => 'Associate Degree',
            'status' => 1,
        ]);

        Qualifications::create([
            'name' => 'Bachelor\'s Degree',
            'status' => 1,
        ]);

        Qualifications::create([
            'name' => 'Master\'s Degree',
            'status' => 1,
        ]);

        Qualifications::create([
            'name' => 'Ph.D.',
            'status' => 1,
        ]);
    }
}
