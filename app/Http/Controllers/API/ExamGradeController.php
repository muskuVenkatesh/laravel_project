<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ExamGradeRepository;
use App\Http\Requests\ExamGradeRequest;
use App\Http\Requests\ExamGradeEditRequest;
use App\Interfaces\ExamGradeInterface;
class ExamGradeController extends Controller
{
    protected $examgradeinterface;
    public function __construct(ExamGradeInterface $examgradeinterface)
    {
        $this->examgradeinterface = $examgradeinterface;
    }

    public function  createExamGrade(ExamGradeRequest $request)
    {
        $Exam = $this->examgradeinterface->createExamGrade($request->validated());
        return response()->json([
            'message' => 'created successfully',
        ], 200);
    }

    public function getAllExamGrade(Request $request)
    {
        $exams = $this->examgradeinterface->getAllExamGrade($request);
        if(empty($exams['data']) || empty($exams['total']))
        {
            return response()->json([
                'message' => "Data Not Found."
            ], 404);
        }
        return response()->json([
            'data' => $exams['data'],
            'total' => $exams['total']
        ], 200);
    }

    public function getExamGradeById($id)
    {
        $exam = $this->examgradeinterface->getExamGradeById($id);
        if ($exam) {
            return response()->json([
                'data' => $exam,

            ], 200);
        }
        return response()->json([
            'message' => 'Exam not found',
        ], 404);
    }

    public function updateExamGrade(ExamGradeEditRequest $request, $id)
    {
        $updatedExam = $this->examgradeinterface->updateExamGrade($id, $request->validated());
        if ($updatedExam) {
            return response()->json([
                'message' => 'Exam updated successfully',
            ], 200);
        }
        return response()->json([
            'message' => 'Exam not found',
        ], 404);
    }

    public function deleteExamGrade($id)
    {
        $updatedExam = $this->examgradeinterface->deleteExamGrade($id);
        if ($updatedExam) {
            return response()->json([
                'message' => 'Exam Deleted successfully',
            ], 200);
        }
        return response()->json([
            'message' => 'Exam not found',
        ], 404);
    }
}
