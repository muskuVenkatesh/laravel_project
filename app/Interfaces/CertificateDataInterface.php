<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface CertificateDataInterface
{
    public function getCertificateFields($data);
    public function generateCertificate(Request $request);
    public function generateCertificateList(Request $request);
    public function deleteCertificatePdf($id);
}
