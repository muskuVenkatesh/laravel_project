<?php

namespace App\Repositories;

use App\Models\ExamMarksEntry;
use App\Models\Section;
use App\Models\Classes;
use App\Models\Subjects;
use App\Models\Attendance;
use App\Models\Branches;
use App\Models\Student;
use App\Models\Exam;
use App\Models\AcademicDetail;
use App\Models\ExamConfig;
use Illuminate\Http\Request;
use App\Interfaces\ExamMarksEntryInterface;
use DB;
use Carbon\Carbon;
use App\Services\DateService;

class ExamMarksEntryRepository implements ExamMarksEntryInterface
{
    protected $exammarksentry;

    public function __construct(ExamMarksEntry $exammarksentry)
    {
        $this->exammarksentry = $exammarksentry;
    }

    public function createExamMarks($data)
    {
        foreach ($data['students'] as $student) {
            $existingRecord = $this->exammarksentry
                                   ->where('branch_id', $data['branch_id'])
                                   ->where('student_id', $student['student_id'])
                                   ->first();
            $classDetails = $student['marks_data']['class_info'];
            $newMarksData = $student['marks_data']['marks'];

            if ($existingRecord) {
                $existingMarksData = json_decode($existingRecord->marks_data, true);
                $existingMarksData['marks'] = array_filter($existingMarksData['marks'], function($item) {
                    return isset($item['subject_id']);
                });
                foreach ($newMarksData as $newSubjectData) {
                    $updated = false;

                    foreach ($existingMarksData['marks'] as $index => $existingSubjectData) {
                        if (isset($existingSubjectData['subject_id']) && $existingSubjectData['subject_id'] == $newSubjectData['subject_id']) {
                            $existingMarksData['marks'][$index]['internal'] = $newSubjectData['internal'];
                            $existingMarksData['marks'][$index]['external'] = $newSubjectData['external'];
                            $existingMarksData['marks'][$index]['isabsent'] = $newSubjectData['isabsent'];
                            $updated = true;
                            break;
                        }
                    }
                    if (!$updated) {
                        $existingMarksData['marks'][] = $newSubjectData;
                    }
                }
                $finalMarksData = [
                    'class_info' => $classDetails,
                    'marks' => $existingMarksData['marks'],
                ];
                $existingRecord->update([
                    'marks_data' => json_encode($finalMarksData),
                ]);
            } else {
                $this->exammarksentry->create([
                    'branch_id' => $data['branch_id'],
                    'student_id' => $student['student_id'],
                    'marks_data' => json_encode($student['marks_data']),
                ]);
            }
        }
        return true;
    }

    public function getExamMarks(Request $request)
    {
        $branch_id = $request->input('branch_id');
        $class_id = $request->input('class_id');
        $exam_id = $request->input('exam_id');
        $section_id = $request->input('section_id');

        $entries = ExamMarksEntry::where('exam_marks_entries.branch_id', $branch_id)
            ->join('branches', 'branches.id', '=', 'exam_marks_entries.branch_id')
            ->join('students', 'students.id', '=', 'exam_marks_entries.student_id')
            ->leftJoin('medium', 'medium.id', '=', 'students.medium_id')
            ->whereRaw("exam_marks_entries.marks_data->'class_info'->>'class_id' = ?", [$class_id])
            ->whereRaw("exam_marks_entries.marks_data->'class_info'->>'exam_id' = ?", [$exam_id])
            ->whereRaw("exam_marks_entries.marks_data->'class_info'->>'section_id' = ?", [$section_id])
            ->select('branches.branch_name', 'medium.name as medium_name', 'medium.id as medium_id', 'exam_marks_entries.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'students.id as student_id')
            ->get();

        $data = $entries->map(function ($entry) use ($branch_id, $class_id, $section_id) {
            $marksData = json_decode($entry->marks_data, true);

            $student = Student::find($entry->student_id);
            $marksDataArray = $marksData['marks'];

            $classId = $marksData['class_info']['class_id'];
            $examId = $marksData['class_info']['exam_id'];
            $sectionId = $marksData['class_info']['section_id'];

            $classInfo = Classes::where('id', $classId)->first(['id', 'name']);
            $sectionInfo = Section::where('id', $sectionId)->first(['id', 'name']);
            $examInfo = Exam::where('id', $examId)->first(['id', 'name']);

            $academic_year = AcademicDetail::where('id', $marksData['class_info']['academic_id'])
            ->select(DB::raw("CONCAT(TO_CHAR(academic_details.start_date, 'Mon YYYY'), ' - ', TO_CHAR(academic_details.end_date, 'Mon YYYY')) as academic_year"))
            ->first();

            $is_Lock = 0;
            $lockReportDate = ExamConfig::where('section_id', $sectionId)
                ->where('class_id', $classId)
                ->value('lock_report');

            if ($lockReportDate && $lockReportDate <= Carbon::today()->toDateString()) {
                $is_Lock = 1;
            }

            $totalMarks = 0;
            $marksWithNames = [];
            foreach ($marksDataArray as $mark) {
                $subjectInfo = Subjects::where('id', $mark['subject_id'])->first(['id', 'name']);
                $marksWithNames[] = [
                    'subject_id' => $mark['subject_id'],
                    'subject_name' => $subjectInfo ? $subjectInfo->name : null,
                    'isabsent' => $mark['isabsent'],
                    'internal' => $mark['internal'],
                    'external' => $mark['external'],
                ];

                if (!$mark['isabsent']) {
                    $totalMarks += ($mark['internal'] + $mark['external']);
                }
            }

            $totalPossibleMarks = count($marksDataArray) * 100;
            $percentage = ($totalMarks / $totalPossibleMarks) * 100;
            $result = $percentage >= 35 ? 'Pass' : 'Fail';

            $sectionRank = $this->calculateRank($totalMarks, $sectionId);
            $classRank = $this->calculateRank($totalMarks, $classId);

            $attendanceCount = Attendance::where('branch_id', $branch_id)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where(function ($query) use ($entry) {
                $query->whereJsonContains('present_student_id', $entry->student_id)
                    ->orWhereJsonContains('absent_student_id', $entry->student_id)
                    ->orWhere('present_student_id', $entry->student_id)
                    ->orWhere('absent_student_id', $entry->student_id);
            })
            ->get();

            $presentCount = $attendanceCount->filter(function ($attendance) use ($entry) {
                $presentIds = is_string($attendance->present_student_id) ? json_decode($attendance->present_student_id, true) : [$attendance->present_student_id];
                return in_array($entry->student_id, (array) $presentIds);
            })->count();

            $absentCount = $attendanceCount->filter(function ($attendance) use ($entry) {
                $absentIds = is_string($attendance->absent_student_id) ? json_decode($attendance->absent_student_id, true) : [$attendance->absent_student_id];
                return in_array($entry->student_id, (array) $absentIds);
            })->count();

            return [
                'id' => $entry->id,
                'branch_name' => $entry->branch_name,
                'branch_id' => $entry->branch_id,
                'student_id' => $entry->student_id,
                'student_name' => $entry->first_name . ' ' . $entry->last_name,
                'admission_no' => $student->admission_no,
                'is_Lock' => $is_Lock,
                'attendance' => [
                    'present_count' => $presentCount,
                    'absent_count' => $absentCount,
                ],
                'marks_data' => [
                    'class_info' => [
                        'class_id' => $classInfo->id ?? null,
                        'class_name' => $classInfo->name ?? null,
                        'section_id' => $sectionInfo->id ?? null,
                        'section_name' => $sectionInfo->name ?? null,
                        'medium_id' => $entry->medium_id,
                        'medium_name' => $entry->medium_name,
                        'exam_id' => $examInfo->id ?? null,
                        'academic_year' => $academic_year->academic_year,
                        'academic_id' => $marksData['class_info']['academic_id'],
                        'entry_type' => $marksData['class_info']['entry_type'],
                    ],
                    'marks' => $marksWithNames,
                    'percentage' => $percentage,
                    'result' => $result,
                    'section_rank' => $sectionRank,
                    'class_rank' => $classRank,
                ],
            ];
        });
        return $data;
    }

    private function calculateRank($totalMarks, $id)
    {
        return rand(1, 30);
    }

    public function getExamMarkById($id)
    {
        $entries = $this->exammarksentry
            ->where('exam_marks_entries.id', $id)
            ->join('branches', 'branches.id', '=', 'exam_marks_entries.branch_id')
            ->join('students', 'students.id', '=', 'exam_marks_entries.student_id')
            ->select('branches.branch_name', 'exam_marks_entries.*', 'students.first_name', 'students.last_name')
            ->get();
            $data = $entries->map(function ($entry) {
                $marksData = json_decode($entry->marks_data, true);

                $classId = $marksData['class_info']['class_id'];
                $examId = $marksData['class_info']['exam_id'];
                $sectionId = $marksData['class_info']['section_id'];

                $classInfo = Classes::where('id', $classId)->first(['id', 'name']);
                $sectionInfo = Section::where('id', $sectionId)->first(['id', 'name']);
                $examInfo = Exam::where('id', $examId)->first(['id', 'name']);

                $is_Lock = 0;
                $lockReportDate = ExamConfig::where('section_id', $sectionId)
                    ->where('class_id', $classId)
                    ->value('lock_report');

                if ($lockReportDate && $lockReportDate <= Carbon::today()->toDateString()) {
                    $is_Lock = 1;
                }
                $marksDataArray = $marksData['marks'];
                $marksWithNames = [];
                foreach ($marksDataArray as $mark) {
                    $subjectInfo = Subjects::where('id', $mark['subject_id'])->first(['id', 'name']);
                    $marksWithNames[] = [
                        'subject_id' => $mark['subject_id'],
                        'subject_name' => $subjectInfo ? $subjectInfo->name : null,
                        'isabsent' => $mark['isabsent'],
                        'internal' => $mark['internal'],
                        'external' => $mark['external'],
                    ];
                }
                return [
                    'id' => $entry->id,
                    'branch_name' => $entry->branch_name,
                    'branch_id' => $entry->branch_id,
                    'student_id' => $entry->student_id,
                    'student_name' => $entry->first_name . ' ' . $entry->last_name,
                    'is_Lock' => $is_Lock,
                    'marks_data' => [
                        'class_info' => [
                            'class_id' => $classInfo->id ?? null,
                            'class_name' => $classInfo->name ?? null,
                            'section_id' => $sectionInfo->id ?? null,
                            'section_name' => $sectionInfo->name ?? null,
                            'exam_id' => $examInfo->id ?? null,
                            'academic_id' => $marksData['class_info']['academic_id'],
                            'entry_type' => $marksData['class_info']['entry_type'],
                        ],
                        'marks' => $marksWithNames,
                    ],
                ];
            });
        return $data;
    }

    public function updateExamMarkById($validdata, $id)
    {
        $data =  $this->exammarksentry->find($id);
        if($data)
        {
             $data->update([
                'branch_id' => $validdata['branch_id'],
                'student_id' => $validdata['student_id'],
                'marks_data' => json_encode($validdata['students']),
            ]);
            return true;
        }
        return false;
    }

    public function getStudentExamMarks(Request $request)
    {
        $branch_id = $request->input('branch_id');
        $student_id = $request->input('student_id');
        $entry = $this->exammarksentry
            ->where('exam_marks_entries.branch_id', $branch_id)
            ->where('exam_marks_entries.student_id', $student_id)
            ->select('exam_marks_entries.*')
            ->first();

        if (!$entry) {
            return response()->json(['message' => 'No records found'], 404);
        }

        $marksData = json_decode($entry->marks_data, true);
        $classId = $marksData['class_info']['class_id'];
        $sectionId = $marksData['class_info']['section_id'];
        $examId = $marksData['class_info']['exam_id'];


        $classInfo = Classes::where('id', $classId)->first(['id', 'name']);
        $sectionInfo = Section::where('id', $sectionId)->first(['id', 'name']);
        $examInfo = Exam::where('id', $examId)->first(['id', 'name']);
        $present_days = $marksData['class_info']['present_days'] ?? 0;
        $class_teacher_remarks =  $marksData['remarks']['class_teacher_remarks'] ?? null;
        $principal_remarks =  $marksData['remarks']['principal_remarks'] ?? null;
        $personality_trait =  $marksData['remarks']['personality_trait'] ?? null;
        $promoted_to =  $marksData['remarks']['promoted_to'] ?? null;
        $student_result = $marksData['remarks']['result'] ?? null;
        $branchInfo = Branches::where('branches.id', $branch_id)
                    ->join('branch_meta', function($join) {
                        $join->on('branch_meta.branch_id', '=', 'branches.id')
                            ->where('branch_meta.name', '=', 'logo_file');
                    })
                    ->first(['branches.id', 'branch_name', 'address as branch_address', 'branch_meta.value as logo_image']);

        $studentInfo = Student::where('students.id', $entry->student_id)
            ->join('parents', 'parents.id', '=', 'students.parent_id')
            ->leftJoin('user_details', 'user_details.user_id', '=', 'students.user_id')
            ->select('students.first_name', 'students.last_name', 'parents.first_name as parent', 'parents.mother_name', 'user_details.date_of_birth', 'students.roll_no', 'students.admission_no')
            ->first();

        $academic_year = AcademicDetail::where('id', $marksData['class_info']['academic_id'])
            ->select(DB::raw("CONCAT(TO_CHAR(academic_details.start_date, 'Mon YYYY'), ' - ', TO_CHAR(academic_details.end_date, 'Mon YYYY')) as academic_year"))
            ->first();

        $studentId = $entry->student_id;
        $marksArray = $marksData['marks'];
        $marksWithNames = [];
        $totalworkingDay = Attendance::count();

        foreach ($marksArray as $mark) {
            $subjectInfo = Subjects::where('subjects.id', $mark['subject_id'])
                ->leftJoin('exam_report_config', 'exam_report_config.subject_id', '=', 'subjects.id')
                ->select('subjects.name as subject_name', 'exam_report_config.max_marks', 'exam_report_config.pass_marks', 'exam_report_config.sequence', 'subjects.id as subjectId')
                ->first();

            $presentattendance = Attendance::where('branch_id', $entry->branch_id)
                ->where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->where('subject_id', $mark['subject_id'])
                ->whereRaw(" ? = ANY (string_to_array(present_student_id, ','))", [$studentId])
                ->count();

            $subjectId = $mark['subject_id'];
            $allMarksForSubject = $this->exammarksentry
                ->where('exam_marks_entries.branch_id', $branch_id)
                ->whereRaw("EXISTS (
                    SELECT 1
                    FROM jsonb_array_elements(marks_data::jsonb -> 'marks') AS elem
                    WHERE (elem ->> 'subject_id')::int = ?
                )", [$subjectId])
                ->get();

            $highestMarks = 0;
            $subjectexternal = [];
            foreach ($allMarksForSubject as $marksdata) {
                $marksData = json_decode($marksdata->marks_data, true);

                foreach ($marksData['marks'] as $marks) {
                    if (isset($marks['subject_id']) && $marks['subject_id'] == $subjectId) {
                        if ($marks['isabsent'] == 0) {
                            $subjectexternal[] = $marks['external'];
                            if ($marks['external'] > $highestMarks) {
                                $highestMarks = $marks['external'];
                            }
                        }
                    }
                }
            }
            $classAverage = !empty($subjectexternal) ? round(array_sum($subjectexternal) / count($subjectexternal), 2) : 0;

            $marksWithNames[] = [
                'subject_id' => $mark['subject_id'],
                'subject_name' => $subjectInfo ? $subjectInfo->subject_name : null,
                'max_marks' => $subjectInfo ? $subjectInfo->max_marks : 0,
                'pass_marks' => $subjectInfo ? $subjectInfo->pass_marks : 0,
                'sequence' => $subjectInfo ? $subjectInfo->sequence : null,
                'isabsent' => $mark['isabsent'],
                'external' => $mark['external'],
                'internal' => $mark['internal'],
                'present_count' => $presentattendance,
                'highest_mark' => $highestMarks,
                'class_average' => $classAverage
            ];
        }
        $dateService = new DateService();
        $data = [
            'id' => $entry->id,
            'branch_name' => $branchInfo->branch_name,
            'branch_address' => $branchInfo->branch_address,
            'branch_id' => $entry->branch_id,
            'logo_image' => $branchInfo->logo_image,
            'student_id' => $entry->student_id,
            'student_name' => $studentInfo->first_name . ' ' . $studentInfo->last_name,
            'student_roll' => $studentInfo->roll_no,
            'admission_no' => $studentInfo->admission_no,
            'total_working_day' => $totalworkingDay,
            'date_of_birth' => $studentInfo->date_of_birth ? $dateService->databaseDateFormate($studentInfo->date_of_birth) : null,
            'parent_name' => $studentInfo->parent . ' ' . $studentInfo->last_name,
            'mother_name' => $studentInfo->mother_name . ' ' . $studentInfo->last_name,
            'marks_data' => [
                'class_id' => $classInfo,
                'section_id' => $sectionInfo,
                'exam_id' => $examInfo,
                'present_days' => $present_days,
                'class_teacher_remarks' => $class_teacher_remarks,
                'principal_remarks' => $principal_remarks,
                'personality_trait' => $personality_trait,
                'promoted_to' => $promoted_to,
                'student_result' => $student_result,
                'academic_id' => $marksData['class_info']['academic_id'],
                'academic_year' => $academic_year->academic_year,
                'entry_type' => $marksData['class_info']['entry_type'],
                'marks' => $marksWithNames
            ]
        ];
        return $data;
    }
}
