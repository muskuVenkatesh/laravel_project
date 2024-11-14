<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\DashboardInterface;
use App\Exceptions\DataNotFoundException;

class DashboardController extends Controller
{
    protected $dashboardInterface;

    public function __construct(DashboardInterface $dashboardInterface)
    {
        $this->dashboardInterface = $dashboardInterface;
    }

    public function getDashboardDetails(Request $request)
    {
        $data = $this->dashboardInterface->getDashboardDetails($request);
        if($data){
            return response()->json([
                'data' => $data
        ],200);
        }else{
            throw new DataNotFoundException('No Data found');
        }
    }

    public function getBirthdayDetails(Request $request)
    {
        $data = $this->dashboardInterface->getBirthdayDetails($request);
        $birthday_students_empty = !count($data['birthday_students']);
        $birthday_staff_empty = !count($data['birthday_staff']);
        if ($birthday_students_empty && $birthday_staff_empty) {
            throw new DataNotFoundException('No birthday details found.');
        }
        return response()->json([
            'birthday_students' => $data['birthday_students'],
            'birthday_staff' => $data['birthday_staff']
        ], 200);
    }


    public function gethomeworkclasscount (Request $request)
    {
        $data = $this->dashboardInterface->gethomeworkclasscount($request);
        if(empty($data))
        {
            throw new DataNotFoundException('No Homewrork Found for the Class or Section.');
        }
        return response()->json([
            'todolist'=>[
            'homework_class_count' => $data
            ]
        ],200);
    }

    public function getBirthdayCount (Request $request)
    {
        $data = $this->dashboardInterface->getBirthdayCount($request);
        if(empty($data)){
            throw new DataNotFoundException('No Birthdays Today');
        }
        return response()->json([
            'todolist'=>[
            'monthly_birthday_count' => $data['monthlyBirthdayCount'],
            'today_birthday_count' => $data['todayBirthdayCount']
            ]
        ],200);
    }

    public function getAttendanceclassCount (Request $request)
    {
        $data = $this->dashboardInterface->getAttendanceclassCount($request);
        if(empty($data)){
            throw new DataNotFoundException('No Attendance Today');
        }
        return response()->json([
            'todolist'=>[
            'attendance_class_count' =>$data
            ]
        ],200);
    }

    public function getStudentPresentCount (Request $request)
    {
        $data = $this->dashboardInterface->getStudentPresentCount($request);
        if(empty($data)){
            throw new DataNotFoundException('No  Data Found For Present Attendance Count');
        }
        return response()->json([
            'todolist'=>[
            'student_present_count' =>$data
            ]
        ],200);
    }

    public function getStudentAbsentCount (Request $request)
    {
        $data = $this->dashboardInterface->getStudentAbsentCount($request);
        if(empty($data)){
            throw new DataNotFoundException('No  Data Found For Absent Attendance Count');
        }
        return response()->json([ 
            'todolist'=>[
            'student_absent_count' =>$data
            ]
        ],200);
    }
    public function getTotalBranchCount()
    {
        $totalBranchCount = $this->dashboardInterface->getTotalBranchCount();
        return response()->json(['total_branch_count' => $totalBranchCount]);
    }

    public function getTotalSetupCount()
    {
        $totalSetupCount = $this->dashboardInterface->getTotalSetupCount();
        return response()->json(['total_setup_count' => $totalSetupCount]);
    }

    public function getTotalMarksReportEntered(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|integer',
        ]);
        $branchId = $request->input('branch_id');
        $totalMarksReport = $this->dashboardInterface->getTotalMarksReportEntered($branchId);
        return response()->json(['total_marks_report' => $totalMarksReport]);
    }

    public function getTotalIncompleteMarksSubjectWise(Request $request)
    {
        $branchId = $request->input('branch_id');
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');
        $incompleteMarksReport = $this->dashboardInterface->getTotalIncompleteMarksSubjectWise($branchId, $classId, $sectionId);

        return response()->json(['incomplete_marks_report' => $incompleteMarksReport]);
    }

    public function getTotalPromotedCount()
    {
        $promotedData = $this->dashboardInterface->getTotalPromotedCount(); 
        return response()->json($promotedData);
    }

    public function getTotalFailedCount()
    {
        $failedData = $this->dashboardInterface->getTotalFailedCount(); 
        return response()->json($failedData);
    }
    
    public function getAggregatedMarksReport(Request $request)
    {
        $branchId = $request->input('branch_id');
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');

        $totalBranchCount = $this->dashboardInterface->getTotalBranchCount();
        $totalSetupCount = $this->dashboardInterface->getTotalSetupCount();
        $totalMarksReport = $this->dashboardInterface->getTotalMarksReportEntered($branchId);
        $incompleteMarksReport = $this->dashboardInterface->getTotalIncompleteMarksSubjectWise($branchId, $classId, $sectionId);
        $promotedData = $this->dashboardInterface->getTotalPromotedCount(); 
        $failedData = $this->dashboardInterface->getTotalFailedCount(); 

        $aggregatedReport = [
            'total_branch_count' => $totalBranchCount,
            'total_setup_count' => $totalSetupCount,
            'total_marks_report' => $totalMarksReport,
            'incomplete_marks_report' => $incompleteMarksReport,
            'promoted_student' => $promotedData,
            'failed_student' => $failedData
        ];
        return response()->json($aggregatedReport);
    }
}
