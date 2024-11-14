<?php

namespace Database\Seeders;

use App\Models\CertificateData;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CertificateDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CertificateData::insert([
            [
                'certificate_type' => 1,
                'school_id' => 1,
                'branch_id' => 1,
                'student_id' => 2,
                'cert_data' => 1,
                'status' => 1,
            ],
            [
                'certificate_type' => 2,
                'school_id' => 2,
                'branch_id' => 2,
                'student_id' => 2,
                'cert_data' => 1,
                'status' => 1,
            ],
        ]);
    }
}
