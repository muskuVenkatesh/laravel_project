<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CertificateTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('certificate_types')->insert([
            [
                'certificate_type' => 'Bonafide Certificate',
                'file_path' => 'pdf_templates\certificate_template.html', 
                'status' => '1',
            ],
            [
                'certificate_type' => 'Character Certificate',
                'file_path' => 'pdf_templates\certificate_template2.html',  
                'status' => '1',
            ],
            [
                'certificate_type' => 'Transfer Certificate',
                'file_path' => 'pdf_templates\certificate_template3.html', 
                'status' => '1',
            ],
           
        ]);
    }
}
