<?php

namespace Database\Seeders;

use App\Models\Medium;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MediumSeeder extends Seeder
{
    public function run(): void
    {
       
        $mediums = [
            ['name' => 'English', 'branch_id' => null],
            ['name' => 'Hindi', 'branch_id' => null],
            ['name' => 'Marathi', 'branch_id' => null],
            ['name' => 'Telugu', 'branch_id' => null],
            ['name' => 'Urdu', 'branch_id' => null],
        ];
        

       
        foreach ($mediums as $medium) {
            Medium::create($medium);
        }
    }
}

