<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\NotificationLog;
use App\Models\Attendance;
use App\Models\Notification;
use App\Interfaces\AttendanceInterface;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\OtpValidation;
use App\Jobs\AttendanceOTPJob;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Events\NotificationCreated;
use Illuminate\Support\Facades\Cache;

class AttendanceRepository implements AttendanceInterface
{
    /**
     * Create a new class instance.
     */
    protected $attendance;
    protected $student;
    protected $otpvalidation;
    public function __construct(Attendance $attendance, Student $student, OtpValidation $otpvalidation)
    {
        $this->attendance = $attendance;
        $this->student = $student;
        $this->otpvalidation = $otpvalidation;
    }

    public function storeAttendance($data)
    {
        $studentsP=[];
        $studentsA=[];
        $validation = "";
        $allStudentIds = [];
        foreach($data['attendance'] as $section=>$students)
        {

            $studentIds = json_decode($students, true);

            $studentIdsP = $this->student->Presentstudent($section, $data['class_id'], $studentIds);

            $studentIdsA = $this->student->Absentstudent($section, $data['class_id'], $studentIds);

            if($data['otp'] == '')
            {
                $this->getOTP($studentIdsA, $data['otp_token']);
            }
            else
            {
                $validation = $this->ValidationOTP($data['otp'], $data['otp_token']);
                if($validation == "true")
                {
                    $studentsP = implode(',', $studentIdsP);
                    $studentsA = implode(',', $studentIdsA);
                    $attendanceData = [
                        'section_id' => $section,
                        'class_id' => $data['class_id'],
                        'subject_id' => $data['subject_id'] ?? null,
                        'present_student_id' => $studentsP,
                        'absent_student_id' => $studentsA,
                        'attendance_date' => Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d'),
                        'branch_id' => $data['branch_id']
                    ];
                    $attendanceId =  $this->attendance->createAttendance($attendanceData);
                    $nId = $this->createNotification($data,$attendanceId);
                    $nIds[] = $nId;
                    $this->createNotificationLog($nId, $data, $studentsA);
                }
            }

        }
        if($validation != "true")
        {
            return $validation;
        }
        event(new NotificationCreated(Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d'), $nIds));
       return $data;
    }

    public function getAttendanceByDate($attendanceDate, $branchId)
    {
        try {
            $formattedDate = Carbon::createFromFormat('d/m/Y', $attendanceDate)->format('Y-m-d');
        } catch (\Exception $e) {
            return collect();
        }
        $data = $this->attendance->where('branch_id', $branchId)
            ->where('attendance_date', $formattedDate)
            ->get('class_id');

        return $data;
    }

    public function getAttendanceReport($attendanceDate, $branchId)
    {
        $data = $this->attendance->getAttendanceReport($attendanceDate, $branchId);

        $combinedData = [];

        foreach ($data as $attendance) {
            $presentStudentIds =  array_filter(
                is_array($attendance->present_student_id)
                ? array_map('intval', $attendance->present_student_id)
                : array_map('intval', explode(',', $attendance->present_student_id))
            );
            $presentStudents = Student::whereIn('id', $presentStudentIds)->withoutTrashed()
            ->select(DB::raw("CONCAT(first_name, ' ', last_name) as full_name"), 'roll_no')
            ->get()
            ->toArray();

            $absentStudentIds = array_filter(
                is_array($attendance->absent_student_id)
                ? array_map('intval', $attendance->absent_student_id)
                : array_map('intval', explode(',', $attendance->absent_student_id))
            );

            $absentStudents = Student::whereIn('id', $absentStudentIds)->withoutTrashed()
            ->select(DB::raw("CONCAT(first_name, ' ', last_name) as full_name"), 'roll_no')
            ->get()
            ->toArray();

            $presentStudents = array_filter($presentStudents);
            $absentStudents = array_filter($absentStudents);

            if(!empty($presentStudents) || !empty($absentStudents))
            {
                foreach ($presentStudents as $presentStudent) {
                    $combinedData['present_data'][] = [
                        'attendance_date' => $attendance->attendance_date,
                        'class_name' => $attendance->class_name,
                        'section_name' => $attendance->section_name,
                        'branch_name' => $attendance->branch_name,
                        'student_name' => $presentStudent['full_name'],
                        'student_roll' => $presentStudent['roll_no']
                    ];
                }
                foreach ($absentStudents as $absentStudent) {
                    $combinedData['absent_data'][] = [
                        'attendance_date' => $attendance->attendance_date,
                        'class_name' => $attendance->class_name,
                        'section_name' => $attendance->section_name,
                        'branch_name' => $attendance->branch_name,
                        'student_name' => $absentStudent['full_name'],
                        'student_roll' => $absentStudent['roll_no']
                    ];
                }
            }

        }
        return $combinedData;
    }
    public function getAttendanceConsolidated($branchId, $start_date, $end_date)
    {
        $data = $this->attendance->getAttendanceConsolidated($branchId, $start_date, $end_date);
        $combinedData =[
                    'present_data' => [],
                    'absent_data' => []
                    ];
        foreach ($data as $attendance) {

            $presentStudentIds = array_filter(
                is_array($attendance->present_student_id)
                ? array_map('intval', $attendance->present_student_id)
                : array_map('intval', explode(',', $attendance->present_student_id))
            );

            $presentStudents = Student::whereIn('id', $presentStudentIds)
            ->select(DB::raw("CONCAT(first_name, ' ', last_name) as full_name"), 'roll_no')
            ->get()
            ->toArray();

            $absentStudentIds =array_filter(
                is_array($attendance->absent_student_id)
                ? array_map('intval', $attendance->absent_student_id)
                : array_map('intval', explode(',', $attendance->absent_student_id))
            );

            $absentStudents = Student::whereIn('id', $absentStudentIds)
            ->select(DB::raw("CONCAT(first_name, ' ', last_name) as full_name"), 'roll_no')
            ->get()
            ->toArray();

            $presentStudents = array_filter($presentStudents);
            $absentStudents = array_filter($absentStudents);

            foreach ($presentStudents as $presentStudent) {
                $combinedData['present_data'][] = [
                    'attendance_date' => $attendance->attendance_date,
                    'class_name' => $attendance->class_name,
                    'section_name' => $attendance->section_name,
                    'branch_name' => $attendance->branch_name,
                    'student_name' => $presentStudent['full_name'],
                    'student_roll' => $presentStudent['roll_no']
                ];
            }
            foreach ($absentStudents as $absentStudent) {
                $combinedData['absent_data'][] = [
                    'attendance_date' => $attendance->attendance_date,
                    'class_name' => $attendance->class_name,
                    'section_name' => $attendance->section_name,
                    'branch_name' => $attendance->branch_name,
                    'student_name' => $absentStudent['full_name'],
                    'student_roll' => $absentStudent['roll_no']
                ];
            }
        }

       if(!empty($combinedData['present_data'] && $combinedData['absent_data']))
       {
         return $combinedData;
       }
    }

    public function getAttendanceNotentered($branchId,$today)
    {
        $data = $this->attendance->getAttendanceNotentered($branchId,$today);
        return $data;
    }
    public function getAttendanceToday($branchId,$today)
    {
        $data = $this->attendance->getAttendanceToday($branchId,$today);
        return $data;
    }


    public function createNotification($data, $attendanceId)
    {
        $notificationString = is_array($data['notification']) ? implode(',', $data['notification']) : $data['notification'];
        $notificationdata = [
            'type_id' => $notificationString,
            'notification_data_id' => $attendanceId,
            'notification_type' => $data['notification_type'],
            'template_id' => 1,
            'status' => 1
        ];
        return Notification::createNotification($notificationdata);
    }

    public function createNotificationLog($nId, $data, $studentIdsA)
    {
        $absentStudentIds = array_filter(
            is_array($studentIdsA)
            ? $studentIdsA
            : explode(',', $studentIdsA)
        );
        foreach ($data['notification'] as $type_id) {
            foreach ($absentStudentIds as $student_id) {
                $emailData = Student::select('parents.alt_email as parent_email', 'parents.alt_phone as parent_phone', 'students.first_name as student_name')
                ->join('parents', 'parents.id', '=', 'students.parent_id')
                ->where('students.id', $student_id)
                ->first();

              $nofication_log_id = NotificationLog::create(['notification_id' => $nId,
                    'type_id' => $type_id,
                    'student_id' => $student_id,
                    'send_to' => $emailData->parent_email,
                    'msg_sender' => "Absent",
                    'msg_status' => 0
                ]);

                $sendTodata = NotificationLog::find($nofication_log_id->id);
                if($sendTodata->type_id == 4)
                {
                    $sendTodata->send_to = $emailData->parent_email;
                    $sendTodata->save();
                }
                else
                {
                    $sendTodata->send_to = $emailData->parent_phone;
                    $sendTodata->save();
                }
            }
        }
    }

    public function getOTP($studentIdsA, $otp_token)
    {
        $cacheKey = 'student_ids_to_send_email';
        $dispatchStatusKey = 'email_dispatched_status';

        $cachedStudentIds = Cache::get($cacheKey, []);
        $mergedStudentIds = array_unique(array_merge($cachedStudentIds, $studentIdsA));

        Cache::put($cacheKey, $mergedStudentIds, 60);
        if (Cache::has($dispatchStatusKey)) {
            return;
        }

        if ($this->isAllIdsCollected($mergedStudentIds)) {
            $branch_name = Student::select('branches.branch_name as branch_name')
                ->join('branches', 'students.branch_id', '=', 'branches.id')
                ->whereIn('students.id', $mergedStudentIds)
                ->distinct()
                ->pluck('branch_name')
                ->first();

            $student_data = Student::whereIn('students.id', $mergedStudentIds)
                ->join('sections', 'sections.id', '=', 'students.section_id')
                ->join('classes', 'classes.id', '=', 'students.class_id')
                ->select(
                    'classes.name as class_name',
                    'sections.name as section_name',
                    'students.first_name as student_name',
                    'students.roll_no'
                )
                ->get()->toArray();

            $otp = mt_rand(100000, 999999);
            $user = Auth::user();

            $this->otpvalidation->Store($otp_token, $otp);
            AttendanceOTPJob::dispatch($user->email, $student_data, $otp, $user->name, $branch_name);

            Cache::put($dispatchStatusKey, true, 60);
            Cache::forget($cacheKey);
        }
    }

    private function isAllIdsCollected(array $mergedStudentIds)
    {
        $minimumRequired = 1;

        return count($mergedStudentIds) >= $minimumRequired;
    }

    public function ValidationOTP($otp, $otp_token)
    {
        $otpData = $this->otpvalidation->where('otp_token', $otp_token)
        ->where('otp', $otp)
        ->first();
        if($otpData)
        {
            $otpData->update(['validate_at' => now()]);
            return "true";
        }
        else
        {
            return 'Otp is Invalid.';
        }
    }

    public function getAttendanceReportBystudentId($branchId, $studentId)
    {
        $data['absent_day'] = [];
        $data['present_day'] = [];
       $absent = Attendance::where(function ($query) use ($studentId) {
            $query->whereRaw("? = ANY(string_to_array(absent_student_id, ','))", [$studentId]);
        })
        ->join('classes', 'classes.id', '=', 'attendances.class_id')
        ->join('sections', 'sections.id', '=', 'attendances.section_id')
        ->where('attendances.branch_id', $branchId)
        ->select(
            'attendances.attendance_date',
            'classes.name as class_name',
            'sections.name as section_name'
        )
        ->selectRaw("'Absent' as Attendance_Status")
        ->get();

        $data['totalabsent']  = $absent->count();
        $data['absent_day'] = $absent;

        $present = Attendance::where(function ($query) use ($studentId) {
            $query->whereRaw("? = ANY(string_to_array(present_student_id, ','))", [$studentId]);
        })
        ->join('classes', 'classes.id', '=', 'attendances.class_id')
        ->join('sections', 'sections.id', '=', 'attendances.section_id')
        ->where('attendances.branch_id', $branchId)
        ->select(
            'attendances.attendance_date',
            'classes.name as class_name',
            'sections.name as section_name'
        )
        ->selectRaw("'Present' as Attendance_Status")
        ->get();
        $data['totalpresent']  = $present->count();
        $data['present_day'] = $present;
        return $data;
    }
}
