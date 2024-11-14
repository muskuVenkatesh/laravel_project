<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Interfaces\ExamInterface;

class ExamRepository implements ExamInterface
{
    protected $exam;

    public function __construct(Exam $exam)
    {
         $this->exam = $exam;
    }

    public function Createexam($data)
    {
        return $this->exam->create($data);
    }

    public function getAllExams(Request $request)
    {
        $limit = $request->input('_limit');
        $allexam = Exam::where('status', 1);
        $total = $allexam->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $allexam->where('name', 'like', "%{$search}%");
        }
        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allexam->orderBy($sortBy, $sortOrder);
        } else {
            $allexam->orderBy('created_at', 'asc');
        }
        if ($limit <= 0) {
            $allexamData = $allexam->get();
        } else {
            $allexamData = $allexam->paginate($limit);
            $allexamData = $allexamData->items();
        }
        return ['data'=>$allexamData, 'total'=>$total];
    }

    public function getExamById($id)
    {
        $exam = $this->exam->find($id);
        if (!$exam) {
            return ['message' => 'Exam not found'];
        }
        return $exam;
    }

    public function updateExam($id, $data)
    {
        $exam = $this->exam->find($id);
        if (!$exam) {
            return ['message' => 'Exam not found'];
        }
        $exam->update($data);
        return $exam;
    }

    public function softDeleteExam($id)
    {
        $exam = $this->exam->find($id);
        $exam->status = 0;
        $exam->save();
        return ['message' => 'Exam deleted successfully'];
    }
}    