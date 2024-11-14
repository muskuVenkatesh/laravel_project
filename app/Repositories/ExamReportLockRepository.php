<?php

namespace App\Repositories;

use App\Models\ExamReportLock;
use Illuminate\Http\Request;
use App\Interfaces\ExamReportLockInterface;

class ExamReportLockRepository implements ExamReportLockInterface
{
    protected $examreportlock;

    public function __construct(ExamReportLock $examreportlock)
    {
        $this->examreportlock = $examreportlock;
    }

    public function CreateExamReportLock($data)
    {
        return $this->examreportlock->create($data);
    }

    public function GetExamReportLock(Request $request)
    {
        $limit = $request->input('_limit');
        $allexamreportlock = ExamReportLock::where('status', 1);
        $total = $allexamreportlock->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $allexamreportlock->where('name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allexamreportlock->orderBy($sortBy, $sortOrder);
        } else {
            $allexamreportlock->orderBy('created_at', 'asc');
        }

        if ($limit <= 0) {
            $allexamreportlockData = $allexamreportlock->get();
        } else {
            $allexamreportlockData = $allexamreportlock->paginate($limit);
            $allexamreportlockData = $allexamreportlockData->items();
        }

        return [
            'data' => $allexamreportlockData,
            'total' => $total,
        ];
    }

    public function GetExamReportLockById($id)
    {
        return $this->examreportlock->find($id);
    }

    public function UpdateExamReportLock($id, $data)
    {
        $examreportlock = $this->examreportlock->find($id);
        if ($examreportlock) {
            $examreportlock->update($data);
            return "Updated Successfully.";
        }
    }

    public function SoftDeleteExamReportLock($id)
    {
        $examreportlock = $this->examreportlock->find($id);
        if ($examreportlock) {
            $examreportlock->status = 0;
            $examreportlock->save();
            return true;
        }
        return false;
    }
}