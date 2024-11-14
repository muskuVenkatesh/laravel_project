<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\ExamGrades;
use App\Interfaces\ExamGradeInterface;

class ExamGradeRepository implements ExamGradeInterface
{
    protected $examgrade;
    public function __construct(ExamGrades $examgrade)
    {
        $this->examgrade = $examgrade;
    }

    public function createExamGrade($data)
    {
        $createdExam = $this->examgrade->create($data);
        return $createdExam; 
    }

    public function getAllExamGrade(Request $request)
    {
        $limit = $request->input('_limit');
        $allexamgrade = ExamGrades::where('exam_grades.status', 1)
        ->join('branches', 'branches.id', '=', 'exam_grades.branch_id')
        ->join('classes', 'classes.id', '=', 'exam_grades.class_id')
        ->select('branches.branch_name', 'exam_grades.*', 'classes.name as class_name');
        
        $total = $allexamgrade->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $allexamgrade->where('name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allexamgrade->orderBy($sortBy, $sortOrder);
        } else {
            $allexamgrade->orderBy('created_at', 'asc');
        }

        if ($limit <= 0) {
            $allexamgradeData = $allexamgrade->get();
        } else {
            $allexamgradeData = $allexamgrade->paginate($limit);
            $allexamgradeData = $allexamgradeData->items();
        }
        return ['data'=>$allexamgradeData,'total'=>$total];
    }

    public function getExamGradeById($id)
    {
        return $this->examgrade->find($id); 
    }

    public function updateExamGrade($id,$data)
    {
        $examgrade = $this->examgrade->find($id); 
        if ($examgrade) {
            $examgrade->update($data); 
            return $examgrade; 
        }
        return null; 
    }

    public function deleteExamGrade($id)
    {
        $examgrade = $this->examgrade->find($id); 
        if ($examgrade) {
            $examgrade->status = 0; 
            $examgrade->save();
            return $examgrade; 
        }
        return null; 
    }
}    