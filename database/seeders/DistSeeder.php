<?php

namespace Database\Seeders;

use App\Models\Dist;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dist::insert([
            ['name' => 'Los Angeles', 'state_id' => 1, 'status' => '1'],
            ['name' => 'San Francisco', 'state_id' => 1, 'status' => '1'],
            ['name' => 'Dallas', 'state_id' => 2, 'status' => '1'],
            ['name' => 'Austin', 'state_id' => 2, 'status' => '1'],
            ['name' => 'New York City', 'state_id' => 3, 'status' => '1'],
        ]);
    }
}
