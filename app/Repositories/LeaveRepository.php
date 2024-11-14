<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Student;
use App\Interfaces\LeaveInterface;
use App\Jobs\LeavemailJob;

class LeaveRepository implements LeaveInterface
{
    protected $leave;
    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    public function CreateLeave($data)
    {
        $datas = $this->leave->create($data);
        if($datas->student_id)
        {
            $emails = Student::where('students.id', $datas->student_id)
            ->join('parents', 'parents.id', '=', 'students.parent_id')
            ->join('branches', 'branches.id', '=', 'students.branch_id')
            ->join('staff', 'staff.branch_id', '=', 'students.branch_id')
            ->join('leaves', function($join) {
                $join->on('leaves.student_id', '=', 'students.id')
                ->whereDate('leaves.created_at', now());
            })
            ->join('users', function($join) {
                $join->on('users.id', '=', 'staff.user_id')
                     ->where('users.roleid', 3);
            })
            ->select('branches.branch_name', 'parents.alt_email', 'staff.email', 'students.first_name', 'leaves.from_date', 'leaves.to_date')
            ->first();
            $emails->leave_date = $emails->from_date . ' to ' . $emails->to_date;
            $emailsArray = $emails->toArray();
            LeavemailJob::dispatch($emailsArray);
        }
    }

    public function getLeaveByStudentId($id)
    {
        $Leave = $this->leave->
        join('students', 'students.id', '=', 'leaves.student_id')
        ->where('leaves.student_id', $id)
        ->select('students.first_name as students_name', 'leaves.*')->get();

        return $Leave;
    }

    public function getLeaves(Request $request)
    {
        $branch_id = $request->input('branch_id');
        $class_id = $request->input('class_id');
        $section_id = $request->input('section_id');

        $leaves = Student::where('students.branch_id', $branch_id);
        if ($class_id) {
            $leaves = $leaves->where('students.class_id', $class_id);
        }
        else if ($section_id) {
            $leaves = $leaves->where('students.section_id', $section_id);
        }
        $leaves = $leaves->join('leaves', 'leaves.student_id', '=', 'students.id')
            ->select('students.first_name as student_name', 'leaves.*')
            ->get();

        $total = $leaves->count();
        return [
            'data' => $leaves,
            'total' => $total
        ];
    }

    public function getLeave($id)
    {
        $leave = $this->leave->find($id);
        return $leave;
    }

    public function UpdateLeave($id, $data)
    {
        $leave = $this->leave->find($id);
        if ($leave) {
            $leave->update($data);
            return $leave;
        }
        return false;
    }

    public function SoftDeleteLeave($id)
    {
        $leave = $this->leave->find($id);
        if ($leave) {
            $leave->status = 0;
            $leave->save();
            return "Leave deleted successfully.";
        }
    }
}
