<?php

namespace App\Repositories;

use App\Models\BranchSubject;
use Illuminate\Http\Request;
use App\Models\Subjects;
use App\Models\Section;

class BranchSubjectRepository
{
    public function __construct(BranchSubject $branchsubject)
    {
        $this->branchsubject = $branchsubject;
    }

    public function Store($data)
    {
        $existingSubjects = [];
        $anyInserted = false;
        foreach ($data['section_id'] as $sectionId) {
            foreach ($data['subjects'] as $subject) {
                $classId = $this->getClassIdBySectionId($sectionId);
                $exists = $this->branchsubject->where([
                    'class_id' => $classId,
                    'section_id' => $sectionId,
                    'subject_id' => $subject['subject_id'],
                ])->exists();

                if (!$exists) {
                    $branchSubject = [
                        'branch_id' => $data['branch_id'],
                        'class_id' => $classId,
                        'section_id' => $sectionId,
                        'subject_id' => $subject['subject_id'],
                        'subject_label' => $subject['subject_label'],
                        'subject_type' => $subject['subject_type'],
                        'subject_code' => $subject['subject_code']
                    ];

                    $this->branchsubject->createBranchSubject($branchSubject);
                    $anyInserted = true;
                }
            }
        }
        if (!$anyInserted) {
            return 'false';
        }
        return "Branch subject created successfully";
    }

    public function GetSubject($id)
    {
        $branchSubject = BranchSubject::with(['branch', 'subject', 'classes', 'scection'])->find($id);
        $response = [
            [
                'id' => $branchSubject->id,
                'branch_id' => $branchSubject->branch_id,
                'branch_name' => $branchSubject->branch ? $branchSubject->branch->branch_name : null,
                'subject_id' => $branchSubject->subject_id,
                'class_id' => $branchSubject->class_id,
                'section_id' => $branchSubject->section_id,
                'subject_name' => $branchSubject->subject ? $branchSubject->subject->name : null,
                'class_name' => $branchSubject->classes ? $branchSubject->classes->name : null,
                'section_name' => $branchSubject->scection ? $branchSubject->scection->name : null,
                'status' => $branchSubject->status,
                'deleted_at' => $branchSubject->deleted_at,
                'created_at' => $branchSubject->created_at,
                'updated_at' => $branchSubject->updated_at,
            ]
        ];
        return $response;
    }

    public function getAll(Request $request, $limit)
    {
        $branch_id = $request->input('branch_id');
        $class_id = $request->input('class_id');
        $section_id = $request->input('section_id');
        $query = BranchSubject::with(['branch', 'subject', 'classes', 'scection'])
            ->where('status', 1)
            ->where('branch_id', $branch_id)
            ->where('section_id', $section_id)
            ->where('class_id', $class_id)
            ->withoutTrashed();
        if ($request->has('q')) {
            $search = $request->input('q');
            $query->whereHas('subject', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            if ($sortBy === 'branch_name') {
                $query->orderBy('branch_id', $sortOrder);
            } elseif ($sortBy === 'subject_name') {
                $query->orderBy('subject_id', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('created_at', 'asc');
        }
        $total = $query->count();

        if ($limit <= 0) {
            $allsubjectData = $query->get();
        } else {
            $allsubjectData = $query->paginate($limit)->items();
        }

        $allsubjectData = collect($allsubjectData)->map(function ($branchSubject) {
            return [
                'id' => $branchSubject->id,
                'branch_id' => $branchSubject->branch_id,
                'branch_name' => $branchSubject->branch ? $branchSubject->branch->branch_name : null,
                'subject_id' => $branchSubject->subject_id,
                'subject_name' => $branchSubject->subject ? $branchSubject->subject->name : null,
                'subject_type' => $branchSubject->subject_type, 
                'class_name' => $branchSubject->classes ? $branchSubject->classes->name : null,
                'section_name' => $branchSubject->scection ? $branchSubject->scection->name : null,
                'status' => $branchSubject->status,
                'deleted_at' => $branchSubject->deleted_at,
                'created_at' => $branchSubject->created_at,
                'updated_at' => $branchSubject->updated_at,
            ];
        });
        return ['data' => $allsubjectData, 'total' => $total];
    }

    public function updateBranchSubject($id, $data)
    {
        $branchSubject = $this->branchsubject->findOrFail($id);
        if ($branchSubject) {
            $branchSubject->update([
                'branch_id' => $data['branch_id'],
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'],
                'subject_id' => $data['subject_id'],
                'subject_label' => $data['subject_label'],
                'subject_type' => $data['subject_type'],
                'subject_code' => $data['subject_code']
            ]);
            return $branchSubject;
        }
        return 'BranchSubject is not get Updated';
    }

    public function delete($id)
    {
        $branchSubject = $this->branchsubject->findOrFail($id);
        $branchSubject->delete();
        $branchSubject->status  = '0';
        $branchSubject->save();
    }

    public function getSubjectByBranch($branch_id)
    {
        $branchSubjects = BranchSubject::with(['subject', 'branch'])
            ->where('branch_id', $branch_id)
            ->get()
            ->map(function ($branchSubject) {
                return [
                    'id' => $branchSubject->id,
                    'branch_id' => $branchSubject->branch_id,
                    'branch_name' => $branchSubject->branch ? $branchSubject->branch->branch_name : null,
                    'subject_id' => $branchSubject->subject_id,
                    'subject_name' => $branchSubject->subject ? $branchSubject->subject->name : null,
                    'status' => $branchSubject->status,
                    'deleted_at' => $branchSubject->deleted_at,
                    'created_at' => $branchSubject->created_at,
                    'updated_at' => $branchSubject->updated_at,
                ];
            });
        return $branchSubjects;
    }

    public function getSubjectByBranchClassSection($branch_id, $class_id, $section_id)
    {
        $data = BranchSubject::select('subjects.name as subject_name', 'subjects.id as subject_id')
            ->where('branch_subjects.status', 1)
            ->where('branch_subjects.branch_id', $branch_id)
            ->where('branch_subjects.section_id', $section_id)
            ->where('branch_subjects.class_id', $class_id)
            ->join('subjects', 'subjects.id', '=', 'branch_subjects.subject_id')
            ->get();
        return $data;
    }

    public function getVerifySubject($data)
    {
        $branchId = $data['branch_id'];
        $classId = $data['class_id'];
        $sectionId = $data['section_id'];
        $missingSubjects = Subjects::leftJoin('branch_subjects', function ($join) use ($branchId, $classId, $sectionId) {
            $join->on('subjects.id', '=', 'branch_subjects.subject_id')
                ->where('branch_subjects.branch_id', $branchId)
                ->where('branch_subjects.class_id', $classId)
                ->where('branch_subjects.section_id', $sectionId);
        })
            ->whereNull('branch_subjects.subject_id')
            ->select('subjects.name as subject_name', 'subjects.id')
            ->get();
        return $missingSubjects;
    }

    public function getSubjectByBranchClass($branch_id, $class_id)
    {
        $data = BranchSubject::select('subjects.name as subject_name', 'subjects.id as subject_id')
            ->where('branch_subjects.status', 1)
            ->where('branch_subjects.branch_id', $branch_id)
            ->where('branch_subjects.class_id', $class_id)
            ->join('subjects', 'subjects.id', '=', 'branch_subjects.subject_id')
            ->distinct()
            ->get();
        return $data;
    }

    public function getClassIdBySectionId($sectionId) {
        $classId = Section::where('id', $sectionId)->value('class_id');
        return $classId;
    }
}
