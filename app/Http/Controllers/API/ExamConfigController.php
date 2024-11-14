<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ExamRepository;
use App\Http\Requests\ExamconfigRequest;
use App\Interfaces\ExamConfigInterface;
use App\Http\Requests\ExamconfigupdateRequest;

class ExamConfigController extends Controller
{
    protected $examconfiginterface;
    public function __construct(ExamConfigInterface $examconfiginterface)
    {
        $this->examconfiginterface = $examconfiginterface;
    }

    public function  createExamConfig(ExamconfigRequest $request)
    {
        $Exam = $this->examconfiginterface->createExamConfig($request->validated());
        return response()->json([
            'message' => 'created successfully',
        ], 200);
    }

    public function getAllExamConfig(Request $request)
    {
        $exams = $this->examconfiginterface->getAllExamConfig($request);
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

    public function getExamConnfigById($id)
    {
        $exam = $this->examconfiginterface->getExamConnfigById($id);
        if ($exam) {
            return response()->json([
                'data' => $exam,

            ], 200);
        } else {
            return response()->json([
                'message' => 'Exam not found',
            ], 404);
        }
    }

    public function updateExamConfig(ExamconfigupdateRequest $request, $id)
    {
        $updatedExam = $this->examconfiginterface->updateExamConfig($id, $request->validated());
        if ($updatedExam) {
            return response()->json([
                'message' => 'Exam updated successfully',
            ], 200);
        }
        return response()->json([
            'message' => 'Exam not found',
        ], 404);
    }

    public function softDeleteExamConfig($id)
    {
        $deletedExam = $this->examconfiginterface->softDeleteExamConfig($id);
        if($deletedExam)
        {
                return response()->json([
                'message' => 'Exam deleted successfully',
            ], 200);
        }
        return response()->json([
            'message' => 'Exam Not Found',
        ], 404);
    }
}
