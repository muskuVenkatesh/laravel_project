<?php

namespace App\Interfaces;

interface AttendanceInterface
{
    public function storeAttendance($data);

    public function getAttendanceByDate($attendanceDate, $branchId);
    public function getAttendanceReport($attendanceDate, $branchId);
    public function getAttendanceConsolidated($branchId,$start_date, $end_date);
    public function getAttendanceNotentered($branchId,$today);
    public function getAttendanceToday($branchId,$today);
    public function getAttendanceReportBystudentId($branchId, $studentId);
}
