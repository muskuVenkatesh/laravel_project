<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ExamConfigInterface 
{
    public function createExamConfig($data);
    public function getAllExamConfig(Request $request);
    public function getExamConnfigById($id);
    public function updateExamConfig($id,$data);
    public function softDeleteExamConfig($id);
}