<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ExamReportLockInterface 
{
    public function CreateExamReportLock($data);
    public function GetExamReportLock(Request $request);
    public function GetExamReportLockById($id);
    public function UpdateExamReportLock($id,$data);
    public function SoftDeleteExamReportLock($id);
}  