<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Interfaces\LeaveInterface;
use App\Http\Requests\LeaveRequest;
use App\Http\Controllers\Controller;
use App\Repositories\LeaveRepository;
use App\Exceptions\DataNotFoundException;

class LeaveController extends Controller
{
    protected $LeaveInterface;

    public function __construct(LeaveInterface $LeaveInterface)
    {
        $this->LeaveInterface = $LeaveInterface;
    }

    public function  CreateLeave(LeaveRequest $request)
    {
        $Leave = $this->LeaveInterface->CreateLeave($request->validated());
        return response()->json([
            'message' => 'created successfully',
        ], 200);
    }

    public function getLeaveByStudentId(Request $request)
    {
        $id = $request->input('student_id');
        $Leave = $this->LeaveInterface->getLeaveByStudentId($id);
        if($Leave && count($Leave)>0)
        {
            return response()->json([
                'data'=> $Leave ],
                200);
        }
        throw new DataNotFoundException('Leave Not Found');
    }

    public function getLeaves(Request $request)
    {
        $Leaves = $this->LeaveInterface->getLeaves($request);
        if($Leaves['data'] && $Leaves['total'])
        {
            return response()->json([
                'data'  => $Leaves['data'],
                'total' => $Leaves['total']
            ], 200);
        }
        throw new DataNotFoundException('Leaves Not Found');
    }

    public function getLeave($id)
    {
        $Leave = $this->LeaveInterface->getLeave($id);
        return response()->json([
            'data'    => $Leave
        ], 200);
    }

    public function UpdateLeave(Request $request, $id)
    {
        $Leave = $this->LeaveInterface->UpdateLeave($id, $request->all());
        if ($Leave) {
            return response()->json([
                'message' => 'Leave updated successfully'
            ], 200);
        } else {
            throw new DataNotFoundException('Leaves Not Found');
        }
    }

    public function SoftDeleteLeave($id)
    {
        $deleteResult = $this->LeaveInterface->SoftDeleteLeave($id);
        if ($deleteResult) {
            return response()->json([
                'message' => $deleteResult,
            ], 200);
        } else {
            throw new DataNotFoundException('Leaves Not Found');
        }
    }
}
