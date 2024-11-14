<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use App\Models\Parents;
use App\Models\Student;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Interfaces\StudentInterface;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRequets\StoreStudentRequest;

class StudentRepository implements StudentInterface
{

    public function __construct(User $user, Student $student, UserDetails $userdetails, Parents $parent)
    {
        $this->user = $user;
        $this->student = $student;
        $this->userdetails = $userdetails;
        $this->parent = $parent;
    }

    public function store($data)
    {
        $parent = $this->parent->where('phone', $data['phone'])->value('id');
        if (empty($parent)) {
            $user_id = $this->user->createUsers($data);
            $parent = $this->parent->createParent($data, $user_id);
            $parentuserdetails = $this->userdetails->createUserDetails($data, $user_id);
        }

        $student = $this->student->createStudent($data, $parent);
        $users = $this->userdetails->createUserDetailsForStudent($data, $student);

        return $student;
    }

    public function getStudent($id)
    {
        $student = $this->student->where('students.id', $id)
        ->join('parents', 'parents.id', '=', 'students.parent_id')
        ->join('classes', 'classes.id', '=', 'students.class_id')
        ->join('branches', 'branches.id', '=', 'students.branch_id')
        ->join('academic_details', 'academic_details.id', '=', 'students.academic_year_id')
        ->join('groups', 'groups.id', '=', 'students.group_id')
        ->join('sections', 'sections.id', '=', 'students.section_id')
        ->join('medium', 'medium.id', '=', 'students.medium_id')
        ->join('languages as first_language', 'first_language.id', '=', 'students.first_lang_id')
        ->join('languages as second_language', 'second_language.id', '=', 'students.second_lang_id')
        ->join('languages as third_language', 'third_language.id', '=', 'students.third_lang_id')
        ->select(
            'branches.branch_name',
            'academic_details.academic_years',
            'parents.first_name as parent_name',
            'students.*',
            'classes.name as class_name',
            'medium.name as medium_name',
            'sections.name as section_name',
            'groups.name as group_name',
            'first_language.name as first_language_name',
            'second_language.name as second_language_name',
            'third_language.name as third_language_name'
        )
        ->first();
        $student_id = $student->user_id;
        $userDetails = $this->userdetails->where('user_id', $student_id)->first();
        $studentArray = $student->toArray();
        $userDetailsArray = $userDetails ? $userDetails->toArray() : [];
        unset($userDetailsArray['id']);
        $mergedData = array_merge($studentArray, $userDetailsArray);
        return $mergedData;
    }

    public function GetStudents($branchId, $classId, $section_id, $search = null, $sortBy = 'first_name', $sortOrder = 'asc', $perPage = 15)
    {
        $query = $this->student->where('students.status', 1)
            ->where('students.branch_id', $branchId)
            ->where('students.class_id', $classId)
            ->where('students.section_id', $section_id)
            ->leftJoin('languages as mother_tongue_language', 'mother_tongue_language.id', '=', 'students.mother_tongue')
            ->leftJoin('languages as first_language', 'first_language.id', '=', 'students.first_lang_id')
            ->leftJoin('languages as second_language', 'second_language.id', '=', 'students.second_lang_id')
            ->leftJoin('languages as third_language', 'third_language.id', '=', 'students.third_lang_id')
            ->join('admission_forms', 'admission_forms.admission_no', '=', 'students.admission_no')
            ->select(
                'students.*',
                'mother_tongue_language.name as mother_tongue_name',
                'first_language.name as first_language_name',
                'second_language.name as second_language_name',
                'third_language.name as third_language_name',
                'admission_forms.previous_school',
                'admission_forms.last_study_course',
                'admission_forms.last_exam_marks'
            )
            ->withoutTrashed()
            ->with([
                'medium' => function ($query) {
                    $query->select('id', 'name')->whereNull('deleted_at');
                },
                'class' => function ($query) {
                    $query->select('id', 'name')->whereNull('deleted_at');
                },
                'section' => function ($query) {
                    $query->select('id', 'name')->whereNull('deleted_at');
                },
            ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('students.first_name', 'like', "%$search%")
                    ->orWhere('students.roll_no', 'like', "%$search%");
            });
        }
        $query->orderBy($sortBy, $sortOrder);
        $students = $query->paginate($perPage);
        $data = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'user_id' => $student->user_id,
                'branch_id' => $student->branch_id,
                'parent_id' => $student->parent_id,
                'academic_year_id' => $student->academic_year_id,
                'roll_no' => $student->roll_no,
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
                'fee_book_no' => $student->fee_book_no,
                'place_of_birth' => $student->place_of_birth,
                'mother_tongue' => $student->mother_tongue_name,
                'physically_challenge' => $student->physically_challenge,
                'neet_applicable' => $student->neet_applicable,
                'transport_required' => $student->transport_required,
                'medium_id' => $student->medium_id,
                'class_id' => $student->class_id,
                'section_id' => $student->section_id,
                'group_id' => $student->group_id,
                'reg_no' => $student->reg_no,
                'emis_no' => $student->emis_no,
                'cse_no' => $student->cse_no,
                'file_no' => $student->file_no,
                'admission_no' => $student->admission_no,
                'application_no' => $student->application_no,
                'joining_quota' => $student->joining_quota,
                'first_lang_id' => $student->first_lang_id,
                'first_language' => $student->first_language_name,
                'second_lang_id' => $student->second_lang_id,
                'second_language' => $student->second_language_name,
                'third_lang_id' => $student->third_lang_id,
                'third_language' => $student->third_language_name,
                'achievements' => $student->achievements,
                'area_of_interest' => $student->area_of_interest,
                'additional_skills' => $student->additional_skills,
                'previous_school' => $student->previous_school,
                'last_study_course' => $student->last_study_course,
                'last_exam_marks' => $student->last_exam_marks,
                'reason_change' => $student->reason_change,
                'last_study_school' => $student->last_study_school,
                'reason_gap' => $student->reason_gap,
                'image' => $student->image,
                'status' => $student->status,
                'deleted_at' => $student->deleted_at,
                'created_at' => $student->created_at,
                'updated_at' => $student->updated_at,
                'class_name' => $student->class?->name,
                'medium_name' => $student->medium?->name,
                'section_name' => $student->section?->name,
            ];
        });
        return $data;
    }

    public function updateStudent($id, $data)
    {
        $student = $this->student->find($id);
        $user_id = $student->user_id;
        $this->student->updateStudent($id, $data);
        $this->userdetails->updateUserDetails($user_id, $data);
    }

    public function DeleStudent($id)
    {
        $student = $this->student->findOrFail($id);
        $student->delete();
        $student->status = '0';
        $student->save();
        return $student;
    }

    public function getStudentByBranch($branch_id)
    {
        $students =  $this->student
        ->join('classes', 'students.class_id', '=', 'classes.id')
        ->join('sections', 'students.section_id', '=', 'sections.id')
        ->join('medium', 'students.medium_id', '=', 'medium.id')
        ->where('students.branch_id', $branch_id)
        ->select(
            'students.*',
            'classes.name as class_name',
            'sections.name as section_name',
            'medium.name as medium_name'
        )
        ->get();

    return $students;
    }

    public function getStudentByClass($class_id, $branch_id, $section_id = null)
    {
        $query = Student::where('class_id', $class_id)
            ->where('branch_id', $branch_id);

        if ($section_id) {
            $query->where('section_id', $section_id);
        }

        return $query->get();
    }

    public function GetInactiveStudents($classId, $search = null, $sortBy = 'first_name', $sortOrder = 'asc', $perPage = 15)
    {
        $query = $this->student->where('status', 0)
            ->where('class_id', $classId)
            ->withoutTrashed()
            ->with([
                'medium' => function ($query) {
                    $query->select('id', 'name')->whereNull('deleted_at');
                },
                'class' => function ($query) {
                    $query->select('id', 'name')->whereNull('deleted_at');
                },
                'section' => function ($query) {
                    $query->select('id', 'name')->whereNull('deleted_at');
                },
            ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('roll_no', 'like', "%$search%");
            });
        }
        $query->orderBy($sortBy, $sortOrder);
        $students = $query->paginate($perPage);
        $data = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'user_id' => $student->user_id,
                'branch_id' => $student->branch_id,
                'parent_id' => $student->parent_id,
                'academic_year_id' => $student->academic_year_id,
                'roll_no' => $student->roll_no,
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
                'fee_book_no' => $student->fee_book_no,
                'place_of_birth' => $student->place_of_birth,
                'mother_tongue' => $student->mother_tongue,
                'physically_challenge' => $student->physically_challenge,
                'neet_applicable' => $student->neet_applicable,
                'transport_required' => $student->transport_required,
                'medium_id' => $student->medium_id,
                'class_id' => $student->class_id,
                'section_id' => $student->section_id,
                'group_id' => $student->group_id,
                'reg_no' => $student->reg_no,
                'emis_no' => $student->emis_no,
                'cse_no' => $student->cse_no,
                'file_no' => $student->file_no,
                'admission_no' => $student->admission_no,
                'application_no' => $student->application_no,
                'joining_quota' => $student->joining_quota,
                'first_lang_id' => $student->first_lang_id,
                'second_lang_id' => $student->second_lang_id,
                'third_lang_id' => $student->third_lang_id,
                'achievements' => $student->achievements,
                'area_of_interest' => $student->area_of_interest,
                'additional_skills' => $student->additional_skills,
                'previous_school' => $student->previous_school,
                'last_study_course' => $student->last_study_course,
                'last_exam_marks' => $student->last_exam_marks,
                'reason_change' => $student->reason_change,
                'last_study_school' => $student->last_study_school,
                'reason_gap' => $student->reason_gap,
                'image' => $student->image,
                'status' => $student->status,
                'deleted_at' => $student->deleted_at,
                'created_at' => $student->created_at,
                'updated_at' => $student->updated_at,
                'class_name' => $student->class->name,
                'medium_name' => $student->medium->name,
                'section_name' => $student->section->name,
            ];
        });
        return $data;
    }

    public function getGapDetails(Request $request)
    {
        $user = Auth::user();
        $role = Role::where('id', $user->roleid)->value('name');
        $studentQuery = Student::join('parents', 'parents.id', '=', 'students.parent_id')
            ->join('classes', 'classes.id', '=', 'students.class_id')
            ->where(function($query) {
                $query->whereNull('parents.first_name')
                      ->orWhere('parents.first_name', '')
                      ->orWhereNull('parents.phone')
                      ->orWhere('parents.phone', '');
            })->select('students.id', 'students.first_name as student_name', 'classes.name as class_name');
        if ($role == 'admin') {
            $school_id = $request->input('school_id');
            if ($school_id) {
                $studentQuery->join('branches', 'branches.id', '=', 'students.branch_id')
                             ->where('branches.school_id', $school_id);
            }
        } elseif ($role == 'management') {
            $branch_id = $request->input('branch_id');
            if ($branch_id) {
                $studentQuery->where('students.branch_id', $branch_id);
            }
        }
        $studentDetails = $studentQuery->get();
        return $studentDetails;
    }
}
