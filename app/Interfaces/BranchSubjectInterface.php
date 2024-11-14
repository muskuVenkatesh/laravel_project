<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface BranchSubjectInterface
{
    public function Store($data);
    public function GetSubject($id);
    public function getAll(Request $request, $perPage);
    public function updateSubject($id,$data);
    public function delete($id);
    public function getSubjectByBranch($id);
    public function getSubjectByBranchClass($branch_id, $class_id);
    public function getSubjectByBranchClassSection($branch_id, $class_id, $section_id);
    public function getVerifySubject($data);
}
