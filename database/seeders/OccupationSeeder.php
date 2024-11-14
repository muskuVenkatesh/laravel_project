<?php

namespace Database\Seeders;

use App\Models\Occupation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Occupation::create([
            'name' => 'Software Developer',
            'status' => 1,
        ]);

        Occupation::create([
            'name' => 'Data Scientist',
            'status' => 1,
        ]);

        Occupation::create([
            'name' => 'Graphic Designer',
            'status' => 1,
        ]);

        Occupation::create([
            'name' => 'Project Manager',
            'status' => 1,
        ]);

        Occupation::create([
            'name' => 'Teacher',
            'status' => 1,
        ]);
    }
}
