<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\CertificateType;

class CertificateTypes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $certificate = [
            [
                'certificate_type' => 'Birth Certificate'
            ],
            [
                'certificate_type' => 'Graduation Certificate'
            ],
            [
                'certificate_type' => 'Transfer Certificate'
            ],
            [
                'certificate_type' => 'Character Certificate'
            ],
            [
                'certificate_type' => 'Bonafide Certificate'
            ]
        ];

        CertificateType::insert($certificate);
    }
}
