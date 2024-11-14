<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface SubjectsInterface
{
    public function getSubject(Request $request, $perPage);
    public function createSubject($data);
    public function updateSubject($data, $id);
    public function deleteSubject($id);
    public function getSubjectTypes();
}
