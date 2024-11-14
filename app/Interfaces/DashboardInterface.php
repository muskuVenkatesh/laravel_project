<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface DashboardInterface
{
    public function getDashboardDetails(Request $request);
    public function getBirthdayDetails(Request $request);
    public function getBirthdayCount(Request $request);
    public function getAttendanceclassCount(Request $request);
    public function getStudentAbsentCount(Request $request);
    public function gethomeworkclasscount(Request $request);
    public function getStudentPresentCount(Request $request);
    public function getTotalBranchCount();
    public function getTotalSetupCount();
    public function  getTotalMarksReportEntered($branchId);
    public function getTotalIncompleteMarksSubjectWise($branchId);
    public function getTotalPromotedCount();
    public function getTotalFailedCount();

}
