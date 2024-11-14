<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface DepartmentInterface
{
    public function deleteDepartment($id);
    public function updateDepartment($data, $id);
    public function createDepartment($data);
    public function getDepartment(Request $request, $perPage);
}
