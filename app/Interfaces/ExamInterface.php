<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ExamInterface 
{
    public function Createexam($data);
    public function getAllExams(Request $request);
    public function getExamById($id);
    public function updateExam($id, $data);
    public function softDeleteExam($id);
}    