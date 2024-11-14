<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Repositories\BranchSubjectRepository;
use App\Http\Requests\StoreBranchSubjectRequest;

class BranchSubjectController extends Controller
{
    protected $branchSubjectRepository;

    public function __construct(BranchSubjectRepository $branchSubjectRepository)
    {
        $this->branchSubjectRepository = $branchSubjectRepository;
    }

    public function storeBranchSubject(StoreBranchSubjectRequest $request)
    {
        $data = $request->validated();
        $branchSubject = $this->branchSubjectRepository->Store($data);

        if($branchSubject == 'false')
        {
            return  response()->json(['All provided subjects already exist for this branch, class, and section'], 409);
        }
        return response()->json(['branch_subject'=>$branchSubject], 201);
    }

    public function GetBranchSubject($id)
    {
        $branchSubject = $this->branchSubjectRepository->GetSubject($id);
        if ($branchSubject) {
            return response()->json([
                'branchsubjects'=>$branchSubject
            ], 200);
        }
    }

    public function GetAllBranchSubjects(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $allsubject = $this->branchSubjectRepository->getAll($request, $perPage);
        if (empty($allsubject['data']) || $allsubject['total'] == 0) {
            throw new DataNotFoundException('No Branch Subjects Found.');
        }
        return response()->json([
            'branchsubjects' => $allsubject['data'],
            'total' => $allsubject['total']
        ], 200);
    }

    public function updateBranchSubject($id,StoreBranchSubjectRequest $request)
    {
       $data = $request->validated();
       $branchSubject = $this->branchSubjectRepository->updateBranchSubject($id, $data);

       if ($branchSubject) {
           return response()->json($branchSubject, 200);
       } else {
           return response()->noContent();
       }
    }

    public function DeleteBranchSubject($id)
    {
        $this->branchSubjectRepository->delete($id);
        return response()->json(['message'=>'Branch Subject Deleted Successfully'], 200);
    }

    public function getSubjectByBranch($branch_id)
    {
        $branchSubject = $this->branchSubjectRepository->getSubjectByBranch($branch_id);
        if (empty($branchSubject) || count($branchSubject) === 0) {
            throw new DataNotFoundException('No subjects found for the specified branch.');
        }
            return response()->json(['branchsubject' =>$branchSubject]);
    }

    public function getSubjectByBranchClassSection(Request $request)
    {
        $branch_id = $request->input('branch_id');
        $class_id = $request->input('class_id');
        $section_id = $request->input('section_id');
        $branchSubject = $this->branchSubjectRepository->getSubjectByBranchClassSection($branch_id, $class_id, $section_id);
        if (empty($branchSubject) || count($branchSubject) === 0) {
            throw new DataNotFoundException('No subjects found for the specified branch or class or section.');
        }
        return response()->json(['branchsubject' =>$branchSubject]);
    }

    public function getVerifySubject(Request $request)
    {
        $validatedData = $request->validate([
            'branch_id' => 'required|integer',
            'class_id' => 'required|integer',
            'section_id' => 'required|integer',
        ]);
        $branchSubjects=$this->branchSubjectRepository->getVerifySubject($validatedData);
        if (empty($branchSubjects) || count($branchSubjects) === 0) {
            throw new DataNotFoundException('No Subjects Found');
        }
        return response()->json(['branchsubject' =>$branchSubjects]);
    }

    public function getSubjectByBranchClass(Request $request)
    {
        $branch_id = $request->input('branch_id');
        $class_id = $request->input('class_id');
        $branchSubject = $this->branchSubjectRepository->getSubjectByBranchClass($branch_id, $class_id);
        if (empty($branchSubject) || count($branchSubject) === 0) {
            throw new DataNotFoundException('No subjects found for the specified branch or class or section.');
        }
        return response()->json(['branchsubject' =>$branchSubject]);
    }
}
