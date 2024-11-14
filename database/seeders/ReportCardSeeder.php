<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IdCardTemplate; // Ensure you import the Role model

class ReportCardSeeder extends Seeder
{
    /**
     * Seed the roles table.
     *
     * @return void
     */
    public function run(): void
    {
        IdCardTemplate::create([
            'id_type' => 'report_card',
            'name' => 'Report Card Template',
            'file_path' => 'pdf_templates/report_card_template.html',
            'html_file_path' => 'pdf_templates/report_card_template.html',
        ]);
    }
}
