<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ExamRepository;
use App\Http\Requests\ExamRequest; 
use App\Interfaces\ExamInterface;
use App\Models\Exam;

class ExamController extends Controller
{
    protected $examInterface;

    public function __construct(ExamInterface $examInterface)
    {
        $this->examInterface = $examInterface;
    }

    public function  Createexam(ExamRequest $request)
    {
        $Leave = $this->examInterface->Createexam($request->validated());
        return response()->json([
            'message' => 'created successfully',
        ], 200);
    }

    public function getAllExams(Request $request)
    {
        $exams = $this->examInterface->getAllExams($request);
        if(empty($exams['data']) || empty($exams['total']))
        {
            return response()->json([
                'message' => 'Data Not Found',
            ], 404);
        }
        return response()->json([
            'data' => $exams['data'],
            'total' => $exams['total']
        ], 200);
    }

    public function getExamById($id)
    {
        $data = Exam::find($id);
        if($data)
        {
            $exam = $this->examInterface->getExamById($id);
            return response()->json([
                'data' => $exam,
            ], 200);
        }
        return response()->json(['message' => "Data not found"], 404);
    }

    public function updateExam(ExamRequest $request, $id)
    {
        $data = Exam::find($id);
        if($data)
        {
            $validatedData = $request->validated();
            $updatedExam = $this->examInterface->updateExam($id, $validatedData);
            return response()->json([
                'message' => 'Updated successfully',
            ], 200);
        }
        return response()->json(['message' => "Data not found"], 404);
    }

    public function softDeleteExam($id)
    {
        $data = Exam::find($id);
        if($data)
        {
            $response = $this->examInterface->softDeleteExam($id);
            return response()->json(['message' => $response], 200);
        }
        return response()->json(['message' => "Data not found"], 404);
    }
}