<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CertificateTypesField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CertificateTypesFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CertificateTypesField::insert([
            [
                'certificate_type_id' => 1,
                'field_label' => 'Date of Birth',
                'field_name' => 'Date_of_Birth',
                'field_type' => 'date',
                'status' => 1,
            ],
            [
                'certificate_type_id' => 1,
                'field_label' => 'Place of Birth',
                'field_name' => 'Place_of_Birth',
                'field_type' => 'string',
                'status' => 1,
            ],
            [
                'certificate_type_id' => 2,
                'field_label' => 'Spouse Name',
                'field_name' => 'Spouse_Name',
                'field_type' => 'string',
                'status' => 1,
            ],
        ]);
    }
}
