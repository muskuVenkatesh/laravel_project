<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface InfoCertificateInterface
{
    public function generateCertificatePdf(Request $request, $html, $filename);
}
