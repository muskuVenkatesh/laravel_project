<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $languages = [
            [
                'name' => 'Hindi',
                'status' => '1'
            ],
            [
                'name' => 'English',
                'status' => '1'
            ],
            [
                'name' => 'Bengali',
                'status' => '1'
            ],
            [
                'name' => 'Telugu',
                'status' => '1'
            ],
            [
                'name' => 'Marathi',
                'status' => '1'
            ],
            [
                'name' => 'Tamil',
                'status' => '1'
            ],
            [
                'name' => 'Urdu',
                'status' => '1'
            ],
            [
                'name' => 'Gujarati',
                'status' => '1'
            ],
            [
                'name' => 'Kannada',
                'status' => '1'
            ],
            [
                'name' => 'Odia',
                'status' => '1'
            ],
            [
                'name' => 'Malayalam',
                'status' => '1'
            ],
            [
                'name' => 'Punjabi',
                'status' => '1'
            ],
            [
                'name' => 'Assamese',
                'status' => '1'
            ],
            [
                'name' => 'Maithili',
                'status' => '1'
            ],
            [
                'name' => 'Santali',
                'status' => '1'
            ],
            [
                'name' => 'Kashmiri',
                'status' => '1'
            ],
            [
                'name' => 'Nepali',
                'status' => '1'
            ],
            [
                'name' => 'Konkani',
                'status' => '1'
            ],
            [
                'name' => 'Sanskrit',
                'status' => '1'
            ],
            [
                'name' => 'Dogri',
                'status' => '1'
            ]
        ];

        Language::insert($languages);
    }
}
