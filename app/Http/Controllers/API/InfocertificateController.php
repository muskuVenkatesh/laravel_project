<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\InfoCertificateInterface;
use App\Http\Requests\CertificateRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\CertificateType;

class InfoCertificateController extends Controller
{
    protected $infocertificateinterface;

    public function __construct(InfoCertificateInterface $infocertificateinterface)
    {
        $this->infocertificateinterface = $infocertificateinterface;
    }

    public function generateCertificatePdf(Request $request)
    {
        $certificate_type = $request->input('certificate_type');

        $htmlFilePath = CertificateType::where('id', $certificate_type)->value('file_path');
        $html = Storage::get($htmlFilePath);
        $pdfContent = $this->infocertificateinterface->generateCertificatePdf($request, $html, 'sample_pdf.pdf');

        return response($pdfContent, 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="sample_pdf.pdf"');

        //return $pdf->download('cirtificate.pdf');
    }
}
