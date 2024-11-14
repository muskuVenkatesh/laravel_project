<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ExamReportLockRepository;
use App\Http\Requests\ExamReportLockRequest; 
use App\Interfaces\ExamReportLockInterface;

class ExamReportLockController extends Controller
{
    protected $examreportlockinterface;

    public function __construct(ExamReportLockInterface $examreportlockinterface)
    {
        $this->examreportlockinterface = $examreportlockinterface;
    }

    public function  CreateExamReportLock(ExamReportLockRequest $request)
    {
        $examReportLock = $this->examreportlockinterface->CreateExamReportLock($request->validated());
        return response()->json([
            'message' => 'created successfully',
        ], 200);
    }

    public function GetExamReportLock(Request $request)
    {
        $examReportLock = $this->examreportlockinterface->GetExamReportLock($request);
        return response()->json([
            'data'  => $examReportLock['data'],
            'total' => $examReportLock['total']],
        200);
    }

    public function GetExamReportLockById($id)
    {
        $examreportlock = $this->examreportlockinterface->GetExamReportLockById($id);
        if (!$examreportlock) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json(['data' => $examreportlock], 200);
    }

    public function UpdateExamReportLock(ExamReportLockRequest $request, $id)
    {
        $examreportlock = $this->examreportlockinterface->UpdateExamReportLock($id, $request->validated());
        if (!$examreportlock) {
            return response()->json(['message' => 'Remark not found'], 404);
        }
        return response()->json([
            'data' => $examreportlock,
        ], 200);
    }

    public function SoftDeleteExamReportLock($id)
    {
        $softDeleted = $this->examreportlockinterface->SoftDeleteExamReportLock($id);
        if (!$softDeleted) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        return response()->json(['message' => 'deleted successfully'], 200);
    }
}    