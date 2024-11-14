<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface LeaveInterface 
{
    public function CreateLeave($data);
    public function getLeaveByStudentId($id);
    public function getLeaves(Request $request);
    public function UpdateLeave($id, $data);
    public function SoftDeleteLeave($id);
    public function getLeave($id);
}