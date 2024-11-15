<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Models\ExamMarksEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ExamMarksExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamMarksEntryExport;
use App\Http\Requests\ExamMarksRequest;
use App\Interfaces\ExamMarksEntryInterface;
use App\Http\Requests\ExamMarksUpdateRequest;

class ExamMarksEntryController extends Controller
{
    protected $exammarksentryInterface;
    public function __construct(ExamMarksEntryInterface $exammarksentryInterface)
    {
        $this->exammarksentryInterface = $exammarksentryInterface;
    }

    public function createExamMarks(ExamMarksRequest $request)
    {
        $examdata =  $this->exammarksentryInterface->createExamMarks($request->validated());
        if($examdata !== '')
        {
            return response()->json([
                'message' => 'created successfully.',
            ], 200);
        }
        return response()->json([
            'message' => 'Something is wrong.',
        ], 404);
    }

    public function getExamMarks(Request $request)
    {
        $examdata =  $this->exammarksentryInterface->getExamMarks($request);
        if($examdata)
        {
            return response()->json([
                'data' => $examdata,
            ], 200);
        }
        return response()->json([
            'message' => 'Data Not Found.',
        ], 404);
    }

    public function getExamMarkById($id)
    {
        $examdata =  $this->exammarksentryInterface->getExamMarkById($id);
        if(!empty($examdata))
        {
            return response()->json([
                'data' => $examdata,
            ], 200);
        }
        return response()->json([
            'message' => 'Data Not Found.',
        ], 404);
    }

    public function updateExamMarkById(ExamMarksUpdateRequest $request, $id)
    {
        $examdata =  $this->exammarksentryInterface->updateExamMarkById($request->validated(), $id);
        if($examdata == true)
        {
            return response()->json([
                'message' => 'updated successfully.',
            ], 200);
        }
        return response()->json([
            'message' => 'Data Not Found.',
        ], 404);
    }

    public function getStudentExamMarks(Request $request)
    {
        $examdata =  $this->exammarksentryInterface->getStudentExamMarks($request);
        if(!empty($examdata))
        {
            return response()->json([
                'data' => $examdata,
            ], 200);
        }
        return response()->json([
            'message' => 'Data Not Found.',
        ], 404);
    }

    public function downloadExamMarksPDF(Request $request)
    {
        $data = $this->exammarksentryInterface->getExamMarks($request);
        $pdf = PDF::loadView('ExamMarks', compact('data'))
        ->setPaper('a3', 'landscape');
        return $pdf->download('exam-marks-report.pdf');
    }

    public function downlaodExamMarksExcel(Request $request)
    {
        $examMarks = $this->exammarksentryInterface->getExamMarks($request);
        return Excel::download(new ExamMarksExport($examMarks), 'exam_marks.xlsx');
    }
}
