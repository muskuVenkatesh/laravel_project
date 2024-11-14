<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ExamGradeInterface 
{
   public function createExamGrade($data);
   public function getAllExamGrade(Request $request);
   public function getExamGradeById($id);
   public function updateExamGrade($id, $data);
   public function deleteExamGrade($id);
}