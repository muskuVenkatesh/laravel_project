<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IdCardTemplate; // Ensure you import the Role model

class ReciptSeeder extends Seeder
{
    /**
     * Seed the roles table.
     *
     * @return void
     */
    public function run(): void
    {
        IdCardTemplate::insert([
            [
                'id_type' => 'receipt',
                'name' => 'Receipt Template Two',
                'file_path' => 'pdf_templates/recipet_template_two.html',
                'html_file_path' => 'pdf_templates/recipet_template_two.html',
            ],
            [
                'id_type' => 'receipt',
                'name' => 'Receipt Template',
                'file_path' => 'pdf_templates/recipet_template.html',
                'html_file_path' => 'pdf_templates/recipet_template.html',
            ],
        ]);
    }
}
