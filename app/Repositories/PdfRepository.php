<?php

namespace App\Repositories;

use App\Interfaces\PDFInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use PDF;
use App\Models\Branches;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use App\Models\AcademicDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Interfaces\ExamMarksEntryInterface;
use App\Interfaces\FeesAcademicSetupInterface;
use App\Interfaces\FeesAcademicPaymentsInterface;
use DB;
class PDFRepository implements PDFInterface
{
    protected $examMarksEntryRepository;
    protected $feesacademicsetupInterface;
    protected $feesacademicpaymentsinterface;
    public function __construct(ExamMarksEntryInterface $examMarksEntryRepository, FeesAcademicSetupInterface $feesacademicsetupInterface, FeesAcademicPaymentsInterface $feesacademicpaymentsinterface)
    {
        $this->examMarksEntryRepository = $examMarksEntryRepository;
        $this->feesacademicsetupInterface = $feesacademicsetupInterface;
        $this->feesacademicpaymentsinterface = $feesacademicpaymentsinterface;
    }

    public function generatePDF(Request $request, $html, $filename = 'document.pdf')
    {
        $examMarks = $this->examMarksEntryRepository->getStudentExamMarks($request);

        if (!$examMarks) {
            return response()->json(['error' => 'No exam marks found for student ID ' . $student_id], 404);
        }

        \Log::info('Exam Marks:', $examMarks);

        $marksData = $examMarks['marks_data'] ?? [];
        $branch_name = $examMarks['branch_name'] ?? 'N/A';
        $branch_address = $examMarks['branch_address'] ?? 'N/A';
        $logo_image = $examMarks['logo_image'] ?? 'N/A';
        $academic_year = $examMarks['marks_data']['academic_year'] ?? 'N/A';

        if (!is_array($marksData['marks'])) {
            return response()->json(['error' => 'Marks data is not an array for student ID ' . $student_id], 400);
        }

        $header_type = $request->input('header_type') ?? 0;
        if ($header_type == 0) {
            $header_data = '
                <div class="header">
                    <div class="school-logo">';
            if ($logo_image !== 'N/A') {
                $header_data .= '<img src="storage/' . $logo_image . '" alt="School Logo">';
            }
            $header_data .= '
                    </div>
                    <div class="school-details">
                        <h2>' . $branch_name . '</h2>
                        <p>' . $branch_address . '</p>
                        <h3>PROGRESS REPORT FOR THE SESSION </h3><b>' . $academic_year . '</b>
                    </div>
                </div>
            ';
        } else {
            $header_data = '';
        }

        $html = $this->populateTemplate($html, $examMarks);
        $html = str_replace('{{header_type}}', $header_data, $html);

        return $html;
    }


    private function populateTemplate($html, $examMarks)
    {
        $studentName = $examMarks['student_name'] ?? 'N/A';
        $parentName = $examMarks['parent_name'] ?? 'N/A';
        $motherName = $examMarks['mother_name'] ?? 'N/A';
        $dateOfBirth = $examMarks['date_of_birth'] ?? 'N/A';
        $studentRoll = $examMarks['student_roll'] ?? 'N/A';
        $admissionNo = $examMarks['admission_no'] ?? 'N/A';
        $branchName = $examMarks['branch_name'] ?? 'N/A';
        $branchAddress = $examMarks['branch_address'] ?? 'N/A';
        $totalWorkingDays = $examMarks['total_working_day'] ?? 'N/A';

        $marksData = $examMarks['marks_data'] ?? [];
        $className = $marksData['class_id']['name'] ?? 'N/A';
        $sectionName = $marksData['section_id']['name'] ?? 'N/A';
        $examName = $marksData['exam_id']['name'] ?? 'N/A';
        $academicYear = $marksData['academic_year'] ?? 'N/A';
        $entryType = $marksData['entry_type'] ?? 'N/A';

        $html = str_replace([
            '{{student_name}}', '{{student_roll}}', '{{admission_no}}', '{{date_of_birth}}',
            '{{parent_name}}', '{{mother_name}}', '{{branch_name}}', '{{branch_address}}',
            '{{total_working_day}}', '{{class_name}}', '{{section_name}}', '{{exam_name}}',
            '{{academic_year}}', '{{entry_type}}'
        ], [
            $studentName, $studentRoll, $admissionNo, $dateOfBirth,
            $parentName, $motherName, $branchName, $branchAddress,
            $totalWorkingDays, $className, $sectionName, $examName,
            $academicYear, $entryType
        ], $html);

        $marksHtml = '';
        $totalPresentCount = 0;

        foreach ($marksData['marks'] as $mark) {
            $subjectName = $mark['subject_name'] ?? 'N/A';
            $internal = $mark['internal'] ?? 'N/A';
            $external = $mark['external'] ?? 'N/A';
            $highestMark = $mark['highest_mark'] ?? 'N/A';
            $classAverage = $mark['class_average'] ?? 'N/A';
            $totalPresentCount += $mark['present_count'] ?? 0;

            $marksHtml .= "
                <tr>
                    <td>{$subjectName}</td>
                    <td>{$internal}</td>
                    <td>{$external}</td>
                    <td>{$internal}</td>
                    <td>{$external}</td>
                    <td>{$highestMark}</td>
                    <td>{$classAverage}</td>
                </tr>";
        }

        $html = str_replace(['{{marks_table}}', '{{totalPresent}}'], [$marksHtml, $totalPresentCount], $html);
        return $html;
    }

    public function generateRecipetPDF(Request $request, $html, $filename = 'document.pdf')
    {
        $fee_academic_id = $request->input('fee_academic_id');
        $response = $this->feesacademicsetupInterface->getAcademicSetupById($fee_academic_id);
        $response['branch_data'] = Branches::where('id', $response['branch_id'])
        ->select('branches.branch_name', 'branches.address as branch_address', 'branches.email as branch_email','branches.phone as branch_phone')
        ->first();

        $response['academic_year'] = AcademicDetail::where('id', $response['academic_id'])
        ->select(DB::raw("CONCAT(TO_CHAR(academic_details.start_date, 'Mon YYYY'), ' - ', TO_CHAR(academic_details.end_date, 'Mon YYYY')) as academic_year"))
        ->first();

        $response['class_name'] = Classes::where('id', $response['class_id'])->value('name');
        $response['section_name'] = Section::where('id', $response['section_id'])->value('name');

        if (!$response) {
            return response()->json(['error' => 'No payment data found'], 404);
        }

        $branchName = $response['branch_data']['branch_name'] ?? 'N/A';
        $branchAddress = $response['branch_data']['branch_address'] ?? 'N/A';
        $branchEmail = $response['branch_data']['branch_email'] ?? 'N/A';
        $branchphone = $response['branch_data']['branch_phone'] ?? 'N/A';
        $academicYear = $response['academic_year']['academic_year'] ?? 'N/A';
        $className = $response['class_name'] ?? 'N/A';
        $sectionName = $response['section_name'] ?? 'N/A';

        $html = str_replace([
            '{{branch_name}}', '{{branch_address}}', '{{branch_email}}', '{{branch_phone}}', '{{class_name}}', '{{section_name}}', '{{academic_year}}'
        ], [
            $branchName, $branchEmail, $branchphone, $branchAddress, $className, $sectionName, $academicYear
        ], $html);

        $amount = $response['amount'] ?? 'N/A';
        $discount = $response['discount'] ?? 'N/A';
        $payTimelineDate = $response['pay_timeline_date'] ?? 'N/A';

        $paymentsHtml = "
            <tr>
                <td>1</td>
                <td>{$payTimelineDate}</td>
                <td>{$discount}</td>
                <td>{$amount}</td>
            </tr>";

        $total_amount = $amount-$discount;
        $html = str_replace('{{payments_table}}', $paymentsHtml, $html);
        $html = str_replace('{{total_amount}}', $total_amount, $html);

        if ($response['parent_recipet'] == 1) {
            $gap = "<div style='margin: 10px 0;'></div>";
            $html = "<div style='margin: 0; padding: 0;'>" . $html . $gap . $html . "</div>";
        }
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdf = PDF::loadHTML($html);
        return $pdf;
    }
}
