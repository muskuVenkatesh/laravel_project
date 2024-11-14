<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Notification;
use Carbon\Carbon;
use DB;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'class_id',
        'section_id',
        'subject_id',
        'present_student_id',
        'absent_student_id',
        'attendance_date'
    ];

    public static function createAttendance($attendanceData)
    {
        return DB::table('attendances')->insertGetId($attendanceData);
    }

    public function getAttendanceReport($attendanceDate, $branchId)
    {
        return Attendance::where('attendances.branch_id', $branchId)
        ->where('attendance_date', $attendanceDate)
        ->join('branches', 'branches.id', '=', 'attendances.branch_id')
        ->join('classes', 'classes.id', '=', 'attendances.class_id')
        ->join('sections', function($join) {
            $join->on('sections.id', '=', 'attendances.section_id')
                 ->on('sections.class_id', '=', 'attendances.class_id');
        })
        ->select('attendances.present_student_id','attendances.absent_student_id','attendances.attendance_date', 'classes.name as class_name', 'branches.branch_name', 'sections.name as section_name')
        ->get();
    }

    public function getAttendanceConsolidated($branchId, $start_date, $end_date)
    {
        return Attendance::where('attendances.branch_id', $branchId)
        ->whereBetween('attendance_date', [$start_date, $end_date])
        ->join('branches', 'branches.id', '=', 'attendances.branch_id')
        ->join('classes', 'classes.id', '=', 'attendances.class_id')
        ->join('sections', function($join) {
            $join->on('sections.id', '=', 'attendances.section_id')
                 ->on('sections.class_id', '=', 'attendances.class_id');
        })
        ->select('attendances.present_student_id', 'attendances.absent_student_id', 'attendances.attendance_date', 'classes.name as class_name', 'branches.branch_name', 'sections.name as section_name')
        ->get();
    }

    public function getAttendanceNotentered($branchId,$today)
    {
        return DB::table('classes')
        ->leftJoin('attendances', function($join) use ($today)
        {
            $join->on('classes.id', '=', 'attendances.class_id')
                 ->where('attendances.attendance_date', '=', $today);
        })
        ->leftJoin('branches', 'branches.id', '=', 'classes.branch_id')
        ->where('classes.branch_id', '=', $branchId)
        ->whereNull('attendances.class_id')
        ->select('classes.name as class_name', 'branches.branch_name')
        ->get();
    }

    public function getAttendanceToday($branchId,$today)
    {
        return Attendance::where('attendances.branch_id', $branchId)
        ->where('attendance_date', $today)
        ->join('branches', 'branches.id', '=', 'attendances.branch_id')
        ->join('classes', 'classes.id', '=', 'attendances.class_id')
        ->select('attendances.attendance_date', 'classes.name as class_name', 'branches.branch_name')
        ->get();
    }
}
