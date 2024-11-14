<?php

namespace App\Repositories;

use App\Interfaces\InfoCertificateInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CertificateData;
use DB;

class InfoCertificateRepository implements InfoCertificateInterface
{
    public function generateCertificatePdf(Request $request, $html, $filename)
    {
        $student_id = $request->input('student_id');
        $response = CertificateData::where('certificate_data.student_id', $student_id)->first();

        $certData = $response['cert_data'];

        if (!empty($certData)) {
            $certDetails = $certData;

            $refNo = $certDetails['ref_no'] ?? 'N/A';
            $issueDate = $certDetails['issue_date'] ?? 'N/A';
            $studentName = $certDetails['student_name'] ?? 'N/A';
            $admNo = $certDetails['adm_no'] ?? 'N/A';
            $fatherName = $certDetails['father_name'] ?? 'N/A';
            $motherName = $certDetails['mother_name'] ?? 'N/A';
            $className = $certDetails['class'] ?? 'N/A';
            $admissionDate = $certDetails['admission_date'] ?? 'N/A';
            $schoolName = $certDetails['school_name'] ?? 'N/A';
            $academicSession = $certDetails['academic_session'] ?? 'N/A';
            $dob = $certDetails['dob'] ?? 'N/A';
            $principalName = $certDetails['principal_name'] ?? 'N/A';

            $html = str_replace([
                '{{ref_no}}', '{{issue_date}}', '{{student_name}}', '{{adm_no}}', '{{father_name}}',
                '{{mother_name}}', '{{class}}', '{{admission_date}}', '{{school_name}}', '{{academic_session}}', '{{dob}}', '{{principal_name}}'
            ], [
                $refNo, $issueDate, $studentName, $admNo, $fatherName,
                $motherName, $className, $admissionDate, $schoolName, $academicSession, $dob, $principalName
            ], $html);

            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfContent = $dompdf->output();

           // $pdf = PDF::loadHTML($html);
             return $pdfContent;
        } else {
            return response()->json(['error' => 'Certificate data not found'], 404);
        }
    }
}
