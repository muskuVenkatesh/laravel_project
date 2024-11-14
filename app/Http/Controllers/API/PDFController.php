<?php

namespace App\Http\Controllers\API;

use App\Interfaces\PDFInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeesAcademicSetup;
use App\Models\Branchmeta;
use App\Models\IdCardTemplate;
use App\Models\FeesStudentAcademicPayments;
use Illuminate\Support\Facades\Storage;
use PDF;

class PDFController extends Controller
{
    protected $pdfRepository;

    public function __construct(PDFInterface $pdfRepository)
    {
        $this->pdfRepository = $pdfRepository;
    }

    public function generatePDF(Request $request)
    {
        $branch_id = $request->input('branch_id');
        $template_id = Branchmeta::where('branch_id', $branch_id)
            ->where('name', 'report_card_template')
            ->first();

        $htmlFilePath = 'pdf_templates/report_card_template.html';
        if ($template_id !== null && $template_id->value != 0) {
            $htmlFilePath = $this->getTemplatePath($template_id->value);
        }
        $html = Storage::get($htmlFilePath);
        $student_ids = $request->input('student_id');
        $pdfContent = '';

        foreach ($student_ids as $index => $student_id) {
            $singleRequest = new Request($request->all());
            $singleRequest->merge(['student_id' => $student_id]);
            $studentHtml = $this->pdfRepository->generatePDF($singleRequest, $html, 'sample_pdf.pdf');
            if ($index < count($student_ids) - 1) {
                $studentHtml .= '<div class="page-break"></div>';
            }
            $pdfContent .= $studentHtml;
        }
        $pdf = PDF::loadHTML($pdfContent);
        return $pdf->download('report_cards.pdf');
    }

    public function generateRecipetPDF(Request $request)
    {
        $fee_academic_id = $request->input('fee_academic_id');
        if($fee_academic_id)
        {
            $template_path = FeesAcademicSetup::where('id', $fee_academic_id)
            ->value('template_id');
        }
        $htmlFilePath = 'pdf_templates/default_template.html';
        if($template_id != null && $template_id != 0)
        {
            $htmlFilePath =  $this->getTemplatePath($template_id);
        }
        try {
            $html = Storage::get($htmlFilePath);
            $pdf = $this->pdfRepository->generateRecipetPDF($request, $html, 'sample_pdf.pdf');
            return $pdf->download('reportcard.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF generation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate PDF'], 500);
        }
    }

    public function getTemplatePath($template_id)
    {
        return IdCardTemplate::where('id', $template_id)
            ->value('html_file_path');
    }
}
