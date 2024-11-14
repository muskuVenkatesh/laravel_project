<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface BranchInterface
{
    public function CreateBranch($data,$school_id);
    public function GetBranchesByschoolId(Request $request, $limit,$school_id);
    public function updateBranchById($data,$school_id);
    public function DeleteBranch($branch_id);
    public function restoreBranch($branch_id);
    public function ActiveBranches($school_id);
    public function GetSingleBranch($branch_id);
}
