<?php

namespace App\Http\Controllers\API;

use Log;
use App\Models\Schools;
use Illuminate\Http\Request;
use App\Interfaces\BranchInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\BranchUpdateRequest;

class BranchController extends Controller
{
    protected $BranchInterface;

    public function __construct(BranchInterface $branchinterface)
    {
        $this->branchinterface = $branchinterface;
    }

    public function BranchStore(BranchRequest $request)
    {
        $validatedData = $request->validated();
        $school_id = $validatedData['school_id'];
        $school = Schools::find($school_id);
        if (!$school) {
            return response()->json([
                'message' => 'School not found',
            ], 404);
        }
        $result = $this->branchinterface->CreateBranch($validatedData, $school_id);
        if (!$result || !isset($result['branch']) || !isset($result['branchmeta'])) {
            return response()->json([
                'message' => 'Branch creation failed. Please try again.',
            ], 500);
        }
        $branch [] = $result['branch'];
        $branchmeta []= $result['branchmeta'];
        return response()->json([
            'branch' => $branch,
            'branchmeta' => $branchmeta,
        ], 201);
    }

    public function updateBranchById(BranchUpdateRequest $request, $branchId)
    {
        $validatedData = $request->validated();
        $result = $this->branchinterface->updateBranchById($validatedData, $branchId);
        $branch [] = $result['branch'];
        $branchmeta []= $result['branchmeta'];
        // $branchsetting[] = $result['schoolbranchsetting'];
        return response()->json([
            'branch' => $branch,
            'branchmeta' => $branchmeta,
            // 'branchSettings' => $branchsetting
        ], 201);
    }

    public function GetBranchesByschoolId(Request $request,$school_id)
    {
        $perPage = $request->input('_limit', 10);
        $branchDetails = $this->branchinterface->GetBranchesByschoolId($request, $perPage,$school_id);
        if (empty($branchDetails['data']) || $branchDetails['total'] == 0){
            throw new DataNotFoundException('No branches found for the specified school.');
        }
        return response()->json([
            'branches' => $branchDetails['data'],
            'total' => $branchDetails['total']
        ], 200);
    }

    public function deleteBranch($branch_id)
    {
        $result = $this->branchinterface->DeleteBranch($branch_id);
        if ($result) {
            return response()->json([
                $result
            ]);
        } else {
            return response()->json([], 404);
        }
    }

    public function BranchRestore($branch_id)
    {
        $result = $this->branchinterface->restoreBranch($branch_id);
        if ($result['statusUpdated']) {
            return response()->json([
                'message' => $result['message'],
            ], 201);
        } else {
            return response()->json([
                'message' => $result['message'],
            ], 404);
        }
    }

    public function ActiveBranches($school_id)
    {
        $activeBranches = $this->branchinterface->ActiveBranches($school_id);
        if (count($activeBranches) === 0) {
            throw new DataNotFoundException('No active branches found for the school.');
        }
        return response()->json([
            'branches' => $activeBranches,
        ], 200);

    }

    public function GetSingleBranch($branch_id)
    {
        $result = $this->branchinterface->GetSingleBranch($branch_id);
        if (!$result) {
            throw new DataNotFoundException('Branch not found');
        }
        return response()->json([
            'branch' => $result[0]
            // 'branchSettings' => $result['branchSettings'],
        ], 200);
    }
}
