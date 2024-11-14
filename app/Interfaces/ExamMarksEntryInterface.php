<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface ExamMarksEntryInterface
{
    public function createExamMarks($data);
    public function getExamMarks(Request $request);
    public function getExamMarkById($id);
    public function updateExamMarkById($data, $id);
    public function getStudentExamMarks(Request $request);
}
