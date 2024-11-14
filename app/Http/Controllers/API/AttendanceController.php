<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\AttendanceInterface;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\AttendanceCreateRequest;

class AttendanceController extends Controller
{
    protected $attendanceinterface;

    public function __construct(AttendanceInterface $attendanceinterface)
    {
        $this->attendanceinterface = $attendanceinterface;
    }

    public function storeAttendance(AttendanceCreateRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->attendanceinterface->storeAttendance($validatedData);
        return response()->json([
            'data' => $data
        ]);
    }

    public function getAttendanceByDate(Request $request)
    {
        $attendanceDate = $request->input('date');
        $branchId = $request->input('branch_id');
        try {
            $date = Carbon::createFromFormat('d/m/Y', $attendanceDate);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Invalid date format. Please use d/m/Y format.'
            ], 400);
        }
        if ($date->isSunday()) {
            return response()->json([
                'message' => 'No attendance data available for Sundays.'
            ], 200);
        }
        $data = $this->attendanceinterface->getAttendanceByDate($attendanceDate, $branchId);
        if (count($data) === 0) {
            throw new DataNotFoundException('Attendance Data not found');
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function getAttendanceReport(Request $request)
    {
        $attendanceDate = $request->input('date');
        $branchId = $request->input('branch_id');
        $attendanceDate = ($attendanceDate) ? $this->getFormattedDate($attendanceDate) : $this->getToday();
        $data = $this->attendanceinterface->getAttendanceReport($attendanceDate, $branchId);
        if (empty($data)){
            throw new DataNotFoundException('No attendance data found for the given branch or date.');
        }
        return response()->json([
            'data' => $data
        ]);
    }

    public function getAttendanceConsolidated(Request $request)
    {
        $branchId = $request->input('branch_id');
        $start_date = $this->getFormattedDate($request->input('start_date'));
        $end_date = $this->getFormattedDate($request->input('end_date'));
        if (!$start_date || !$end_date) {
            return response()->json([
                'error' => 'Please provide valid start and end dates.'
            ], 400);
        }
        $data = $this->attendanceinterface->getAttendanceConsolidated($branchId, $start_date, $end_date);
        if (empty($data)){
            throw new DataNotFoundException('No attendance data found for the given branch or date range.');
        }
        return response()->json([
                'data' => $data
            ]);
    }

    public function getAttendanceNotentered(Request $request)
    {
        $branchId = $request->input('branch_id');
        if (empty($branchId)) {
            return response()->json([
                'error' => 'Branch ID is required.'
            ], 400);
        }
        $today = $this->getToday();
        $data = $this->attendanceinterface->getAttendanceNotentered($branchId, $today);
        if (empty($data)){
            throw new DataNotFoundException('No attendance data found for the given branch and date.');
        }
        return response()->json([
            'data' => $data
        ]);
    }

    public function getAttendanceToday(Request $request)
    {
        $branchId = $request->input('branch_id');
        $today = $this->getToday();
        $data = $this->attendanceinterface->getAttendanceToday($branchId,$today);
        if (empty($data)) {
             throw new DataNotFoundException('No attendance data found for today.');
        }
        return response()->json([
            'data' => $data
        ]);
    }

    public function getOTPVerification(Request $request)
    {
        $data = $this->attendanceinterface->getOTP();
        return response()->json([
            'otp' => $data
        ]);
    }

    public function getAttendanceReportBystudentId(Request $request)
    {
        $branchId = $request->input('branch_id');
        $studentId = $request->input('student_id');

        $data = $this->attendanceinterface->getAttendanceReportBystudentId($branchId, $studentId);
        if ($data['totalpresent'] === 0 && $data['totalabsent'] === 0) {
            throw new DataNotFoundException('No attendance data found for the specified student.');
        }
        return response()->json([
            'data' => $data
        ]);
    }

}
