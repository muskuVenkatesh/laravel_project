<?php

namespace App\Repositories;

use DB;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Classes;
use App\Models\Parents;
use App\Models\Schools;
use App\Models\Student;
use App\Models\ExamMarksEntry;
use App\Models\Subjects; 
use App\Models\Branches;
use App\Models\Homework;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\DashboardInterface;

class DashboardRepository implements DashboardInterface
{
    public function getDashboardDetails(Request $request)
    {
        $total_students = $this->getStudentsDetails($request);
        $total_parents = $this->getParentsDetails($request);
        $total_staff = $this->getStaffDetails($request);
        $total_classes= $this->getclassDetails($request);
        $total_branches=$this->getBranchDetails($request);
        return [
            'total_parents' => $total_parents,
            'total_students' => $total_students,
            'total_staff'=>$total_staff,
            'total_classes' =>$total_classes,
            'total_branches' =>$total_branches,
        ];
    }

    public function getStudentsDetails(Request $request)
    {
        $user = Auth::user();
        $role = Role::where('id', $user->roleid)->value('name');
        $query = Branches::join('students', 'students.branch_id', 'branches.id');
        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if (!$school_id) {
                return ['error' => 'school_id is required for admin'];
            }
            $query->where('branches.school_id', $school_id);
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if (!$branch_id) {
                return ['error' => 'branch_id is required for management'];
            }
            $query->where('branches.id', $branch_id);
        }
        $total_students = $query->count();
        return $total_students;
    }

    public function getParentsDetails(Request $request)
    {
        $user = Auth::user();
        $role = Role::where('id', $user->roleid)->value('name');
        $query = Branches::join('parents', 'parents.branch_id', 'branches.id');
        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if (!$school_id) {
                return ['error' => 'school_id is required for admin'];
            }
            $query->where('branches.school_id', $school_id);
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if (!$branch_id) {
                return ['error' => 'branch_id is required for management'];
            }
            $query->where('branches.id', $branch_id);
        }
        $total_parents = $query->count();
        return $total_parents;
    }

    public function getBranchDetails(Request $request)
    {
        $user = Auth::user();
        $role = Role::where('id', $user->roleid)->value('name');
        $query = Branches::query();
        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if (!$school_id) {
                return ['error' => 'school_id is required for admin'];
            }
            $query->where('branches.school_id', $school_id);
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if (!$branch_id) {
                return ['error' => 'branch_id is required for management'];
            }
            $query->where('branches.id', $branch_id);
        }
        $total_branches = $query->count();
        return $total_branches;
    }

    public function getStaffDetails(Request $request)
    {
        $user = Auth::user();
        $role = Role::where('id', $user->roleid)->value('name');
        $query = Branches::join('staff', 'staff.branch_id', 'branches.id');
        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if (!$school_id) {
                return ['error' => 'school_id is required for admin'];
            }
            $query->where('branches.school_id', $school_id);
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if (!$branch_id) {
                return ['error' => 'branch_id is required for management'];
            }
            $query->where('branches.id', $branch_id);
        }
        $total_staff = $query->count();
        return $total_staff;
    }
    public function getclassDetails(Request $request)
    {
        $user = Auth::user();
        $role = Role::where('id', $user->roleid)->value('name');
        $query = Branches::join('classes', 'classes.branch_id', 'branches.id');
        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if (!$school_id) {
                return ['error' => 'school_id is required for admin'];
            }
            $query->where('branches.school_id', $school_id);
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if (!$branch_id) {
                return ['error' => 'branch_id is required for management'];
            }
            $query->where('branches.id', $branch_id);
        }
        $total_classes = $query->count();
        return $total_classes;
    }

    public function getBirthdayDetails(Request $request)
    {
        $birthday_students = $this->getStudentBirthdayDetails($request);
        $birthday_staff = $this ->getStaffbirthdayDetails($request);
        return [
            'birthday_students' => $birthday_students,
            'birthday_staff' => $birthday_staff
        ];
    }

    public function getStaffbirthdayDetails(Request $request)
    {
        $user = Auth::user();
        $role = Role::where('id', $user->roleid)->value('name');
        $query = Staff::join('branches', 'branches.id', '=', 'staff.branch_id');
        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if (!$school_id) {
                return ['error' => 'school_id is required for admin'];
            }
            $query->where('branches.school_id', $school_id);
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if (!$branch_id) {
                return ['error' => 'branch_id is required for management'];
            }
            $query->where('branches.id', $branch_id);
        }
        $query->join('user_details', function($join) {
            $join->on('user_details.user_id', '=', 'staff.user_id')
                ->whereColumn('user_details.branch_id', '=', 'staff.branch_id');
        })
        ->join('departments', 'departments.id', '=', 'staff.department')
        ->select('staff.first_name', 'departments.name as department_name', 'user_details.date_of_birth');
        $currentMonth = now()->month;
        $query->whereMonth('user_details.date_of_birth', $currentMonth);
        $birthdaydata = $query->get();
        return $birthdaydata;
    }

    public function getStudentBirthdayDetails(Request $request)
    {
        $user = Auth::user();
        $role = Role::where('id', $user->roleid)->value('name');
        $query = Student::join('branches', 'branches.id', '=', 'students.branch_id')
                        ->where('students.status', 1);
        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if (!$school_id) {
                return ['error' => 'school_id is required for admin'];
            }
            $query->where('branches.school_id', $school_id);
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if (!$branch_id) {
                return ['error' => 'branch_id is required for management'];
            }
            $query->where('branches.id', $branch_id);
        }
        $query->join('user_details', function ($join) {
            $join->on('user_details.user_id', '=', 'students.user_id')
            ->whereColumn('user_details.branch_id', 'students.branch_id');
        })
        ->join('classes', 'classes.id', '=', 'students.class_id')
        ->join('sections', 'sections.id', '=', 'students.section_id')
        ->select('students.first_name', 'classes.name as class_name', 'sections.name as section_name', 'user_details.date_of_birth');
        $currentMonth = now()->month;
        $query->whereMonth('user_details.date_of_birth', $currentMonth);
        $birthdaydata = $query->get();
        return $birthdaydata;
    }

    public function gethomeworkclasscount(Request $request)
    {
        $user = Auth::user();
        $role = Role::where('id', $user->roleid)->value('name');

        $query = Homework::join('branches', 'branches.id', '=', 'homework.branch_id')
            ->join('classes', 'classes.id', '=', 'homework.class_id')
            ->join('sections', 'sections.id', '=', 'homework.section_id');
        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if (!$school_id) {
                return ['error' => 'school_id is required for admin'];
            }
            $query->where('branches.school_id', $school_id);
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if (!$branch_id) {
                return ['error' => 'branch_id is required for management'];
            }
            $query->where('branches.id', $branch_id);
        }
        $today = Carbon::now()->format('Y-m-d');
        $query->whereDate('homework.date', $today);
        $classCount = $query->groupBy('homework.class_id')
            ->count(DB::raw('homework.class_id'));
        return $classCount;
    }

    public function getBirthdayCount(Request $request)
    {
        $user = Auth::user();
        $role = Role::where('id', $user->roleid)->value('name');
        $monthlyBirthdayCount = 0;
        $todayBirthdayCount = 0;
        $today = now()->format('Y-m-d');
        $currentMonth = now()->month;
        // Students
        $studentQuery = Student::join('branches', 'branches.id', '=', 'students.branch_id')
                            ->join('user_details', 'user_details.user_id', '=', 'students.user_id');
        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if ($school_id) {
                $studentQuery->where('branches.school_id', $school_id);
            }
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if ($branch_id) {
                $studentQuery->where('branches.id', $branch_id);
            }
        }
        $monthlyBirthdayCount += $studentQuery->whereMonth('user_details.date_of_birth', $currentMonth)->count();
        $todayBirthdayCount += $studentQuery->whereDate('user_details.date_of_birth', $today)->count();
        // Staff
        $staffQuery = Staff::join('branches', 'branches.id', '=', 'staff.branch_id')
                        ->join('user_details', 'user_details.user_id', '=', 'staff.user_id');

        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if ($school_id) {
                $staffQuery->where('branches.school_id', $school_id);
            }
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if ($branch_id) {
                $staffQuery->where('branches.id', $branch_id);
            }
        }

        $monthlyBirthdayCount += $staffQuery->whereMonth('user_details.date_of_birth', $currentMonth)->count();
        $todayBirthdayCount += $staffQuery->whereDate('user_details.date_of_birth', $today)->count();
        // Parents
        $parentQuery = Parents::join('branches', 'branches.id', '=', 'parents.branch_id')
                            ->join('user_details', 'user_details.user_id', '=', 'parents.user_id');
        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if ($school_id) {
                $parentQuery->where('branches.school_id', $school_id);
            }
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if ($branch_id) {
                $parentQuery->where('branches.id', $branch_id);
            }
        }
        $monthlyBirthdayCount += $parentQuery->whereMonth('user_details.date_of_birth', $currentMonth)->count();
        $todayBirthdayCount += $parentQuery->whereDate('user_details.date_of_birth', $today)->count();

        return [
            'monthlyBirthdayCount' => $monthlyBirthdayCount,
            'todayBirthdayCount' => $todayBirthdayCount
        ];
    }

    public function getAttendanceclassCount(Request $request)
    {
        $user = Auth::user();
        $role = Role::where('id', $user->roleid)->value('name');
        $query = Classes::join('branches', 'branches.id', '=', 'classes.branch_id')
                        ->whereNull('classes.deleted_at')
                        ->select('classes.id', 'classes.branch_id');
            if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if (!$school_id) {
                return ['error' => 'school_id is required for admin'];
            }
            $query->where('branches.school_id', $school_id);
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if (!$branch_id) {
                return ['error' => 'branch_id is required for management'];
            }
            $query->where('branches.id', $branch_id);
        }
        $classCount = $query->distinct('classes.id')->count('classes.id');
        return $classCount;
    }

    public function getStudentPresentCount(Request $request)
    {
        $user = Auth::user();
        $role = Role::where('id', $user->roleid)->value('name');
        $query = Attendance::join('branches', 'branches.id', '=', 'attendances.branch_id')
        ->join('classes', 'classes.id', '=', 'attendances.class_id')
        ->join('sections', 'sections.id', '=', 'attendances.section_id')
        ->whereNotNull('attendances.present_student_id');

        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if ($school_id) {
                $query->where('branches.school_id', $school_id);
            }
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if ($branch_id) {
                $query->where('branches.id', $branch_id);
            }
        }
        $today = Carbon::now()->format('Y-m-d');
        $query->whereDate('attendances.attendance_date', $today);
        $attendances = $query->get();
        $presentCount = 0;
        foreach ($attendances as $attendance) {
            $presentStudentIds = explode(',', $attendance->present_student_id);
            $presentCount += count($presentStudentIds);
        }
        return $presentCount;
    }

    public function getStudentAbsentCount(Request $request)
    {
        $user = Auth::user();
        $role = Role::where('id', $user->roleid)->value('name');
        $query = Attendance::join('branches', 'branches.id', '=', 'attendances.branch_id')
                            ->join('classes', 'classes.id', '=', 'attendances.class_id')
                            ->join('sections', 'sections.id', '=', 'attendances.section_id')
                            ->whereNotNull('attendances.absent_student_id');
        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if ($school_id) {
                $query->where('branches.school_id', $school_id);
            }
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if ($branch_id) {
                $query->where('branches.id', $branch_id);
            }
        }
        $today = Carbon::now()->format('Y-m-d');
        $query->whereDate('attendances.attendance_date', $today);
        $absentCounts = $query->get();
        $abasentcount = 0;
        foreach ($absentCounts as $absentattendance) {
            $absentStudentIds = explode(',', $absentattendance->absent_student_id);
            $abasentcount += count($absentStudentIds);
        }
        return $abasentcount;
    }

    public function getTotalBranchCount()
    
    {
        return branches::count();
    }

    public function getTotalSetupCount()
    {
        return branches::where('status', 1)->count(); 
    }

    public function getTotalMarksReportEntered($branchId)
    {
        $totalBranchStudentCount = Student::where('branch_id', $branchId)->count(); 
        $addedStudentIdsCount = ExamMarksEntry::distinct('student_id')->count('student_id');
        
        if ($addedStudentIdsCount === 0) {
            return 0; 
        }
        
        return [
            'total_branch_student_count' => $totalBranchStudentCount,
            'added_student_ids_count' => $addedStudentIdsCount,
        ];
    }
    
    public function getTotalIncompleteMarksSubjectWise($branchId)
    {
        $entries = ExamMarksEntry::whereHas('student', function ($query) use ($branchId) {
            $query->where('branch_id', $branchId)
                ->whereNull('deleted_at');
        })->get();
    
        $subjectStats = [];
        foreach ($entries as $entry) {
            $marksData = json_decode($entry->marks_data, true);
    
            if (!empty($marksData)) {
                foreach ($marksData as $subject) {
                    if (isset($subject['subject_id']) && isset($subject['marks']) && isset($subject['isabsent'])) {
                        $subjectId = $subject['subject_id'];
                        $subjectName = Subjects::where('id', $subjectId)->value('name');
                        if (!isset($subjectStats[$subjectId])) {
                            $subjectStats[$subjectId] = [
                                'subject_name' => $subjectName,
                                'incomplete' => 0,
                            ];
                        }
                        if ($subject['marks'] == 0 || $subject['isabsent'] == 1) {
                            $subjectStats[$subjectId]['incomplete']++;
                        }
                    }
                }
            }
        }
        $result = [
            'total_incomplete_reports' => 0,
            'subject_wise_incomplete' => []
        ];

        foreach ($subjectStats as $data) {
            $result['subject_wise_incomplete'][$data['subject_name']] = $data['incomplete'];
            $result['total_incomplete_reports'] += $data['incomplete'];
        }
        return $result;
    }

    public function getTotalPromotedCount()
    {
        $totalPromoted = 120; 
        return [
            'total_promoted' => $totalPromoted,
        ];
    }

    public function getTotalFailedCount()
    {
        $totalFailed = 30; 
        return [
            'total_failed' => $totalFailed,
        ];
    }
}