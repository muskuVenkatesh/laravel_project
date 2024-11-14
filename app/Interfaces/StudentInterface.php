<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface StudentInterface
{
    public function store($data);
    public function getStudent($id);
    public function getStudentByBranch($branch_id);
    public function getStudentByClass($class_id, $branch_id, $section_id);
    public function GetStudents($branchId, $classId, $section_id, $search = null, $sortBy = 'first_name', $sortOrder = 'asc', $perPage = 15);
    public function updateStudent($id, $data);
    public function DeleStudent($id);
    public function GetInactiveStudents($classId, $search = null, $sortBy = 'first_name', $sortOrder = 'asc', $perPage = 15);
    public function getGapDetails(Request $request);
}
