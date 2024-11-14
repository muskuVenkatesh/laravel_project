<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CurrencyTypes;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'RUPE',
            'USD',
        ];
        $symbol = [
            '&#8377;',
            '&#36;'
        ];
        foreach ($names as $key => $name) {
            CurrencyTypes::create([
                'name' => $name,
                'symbol' => $symbol[$key],
                'status' => '1'
            ]);
        }
    }
}
