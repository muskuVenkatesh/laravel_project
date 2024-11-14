<?php

namespace App\Repositories;

use App\Interfaces\StudentParentDetailsInterface;
use App\Models\Student; 
use App\Models\Parents; 
use App\Models\UserDetails; 
use Illuminate\Http\Request;

class StudentParentDetailsRepository implements StudentParentDetailsInterface
{
    public function getStudentParentDetails(Request $request)
    {
        $studentId = $request->input('student_id');
        if($studentId)
        {
            $student = Student::where('students.id', $studentId)
            ->join('user_details', 'user_details.user_id', '=', 'students.user_id')
            ->leftJoin('classes', 'classes.id', '=', 'students.class_id')
            ->leftJoin('sections', 'sections.id', '=', 'students.section_id')
            ->leftJoin('medium', 'medium.id', '=', 'students.medium_id')
            ->leftJoin('groups', 'groups.id', '=', 'students.group_id')
            ->leftJoin('languages as first_lang', 'first_lang.id', '=', 'students.first_lang_id')
            ->leftJoin('languages as second_lang', 'second_lang.id', '=', 'students.second_lang_id')
            ->leftJoin('languages as third_lang', 'third_lang.id', '=', 'students.third_lang_id')
            ->leftJoin('academic_details', 'academic_details.id', '=', 'students.academic_year_id')
            ->join('branches', 'branches.id', '=', 'students.branch_id')
            ->select(
            'branches.branch_name', 
            'academic_details.academic_years',
            'students.roll_no',
            'students.first_name',
            'students.middle_name',
            'students.last_name',
            'classes.name as class_name', 
            'sections.name as section_name', 
            'medium.name as medium_name',
            'groups.name as group_name',
            'students.fee_book_no',
            'students.place_of_birth',
            'students.mother_tongue',
            'students.physically_challenge',
            'students.neet_applicable',
            'students.transport_required',
            'students.reg_no',
            'students.emis_no',
            'students.cse_no',
            'students.file_no',
            'students.admission_no',
            'students.application_no',
            'students.admission_date',
            'students.joining_quota',
            'first_lang.name as first_language',
            'second_lang.name as second_language',
            'third_lang.name as third_language',
            'students.achievements',
            'students.area_of_interest',
            'students.additional_skills',
            'students.image',
            'students.parent_id',
            'students.user_id',
            'students.id',
            'user_details.date_of_birth',
            'user_details.gender',
            'user_details.blood_group',
            'user_details.religion',
            'user_details.cast',
            'user_details.image',
            'user_details.mother_tongue',
            'user_details.aadhaar_card_no',
            'user_details.pan_card_no')
            ->first();

            $student['fatherDetails'] = $this->getfatherDetails($student->parent_id); 

            $student['motherDetails'] = $this->getMotherDetails($student->parent_id);     
            
            $student['communicationDetails'] = $this->getcommunicationDetails($student->user_id);

            $student['siblingDetails'] = $this->getsiblingDetails($student->parent_id, $student->id);

            return $student;
        }
        return  null;
    }

    public function getMotherDetails($parent_id)
    {
       return  Parents::where('parents.id', $parent_id)
       ->leftJoin('occupations', 'occupations.id', '=', 'parents.mother_occupation') 
       ->leftJoin('qualifications', 'qualifications.id', '=', 'parents.mother_education') 
        ->select('mother_name',
            'mother_phone', 
            'mother_email',
            'qualifications.name as mother_education',
            'occupations.name as mother_occupation',
            'mother_annual_income', 
            'mother_aadhaar_no', 
            'mother_pan_card', 
            'mother_dob'
            )
        ->first();
    }

    public function getfatherDetails($parent_id)
    {
        return Parents::where('parents.id', $parent_id)
        ->join('user_details', 'user_details.user_id', '=', 'parents.user_id')
        ->leftJoin('occupations', 'occupations.id', '=', 'parents.occupation') 
        ->leftJoin('qualifications', 'qualifications.id', '=', 'parents.education') 
        ->select(
            'parents.parent_uid', 
            'parents.first_name', 
            'parents.middle_name', 
            'parents.last_name',
            'parents.phone', 
            'parents.alt_phone', 
            'parents.alt_email', 
            'qualifications.name as education', 
            'occupations.name as occupation_name', 
            'parents.annual_income',
            'user_details.date_of_birth',
            'user_details.gender',
            'user_details.blood_group',
            'user_details.religion',
            'user_details.cast',
            'user_details.image',
            'user_details.mother_tongue',
            'user_details.aadhaar_card_no',
            'user_details.pan_card_no',
            'user_details.address',
            'user_details.city',
            'user_details.state',
            'user_details.country',
            'user_details.pin'
        )
        ->first();
    }

    public function getcommunicationDetails($user_id)
    {
        return UserDetails::where('user_id', $user_id)
        ->select('address',
            'city',
            'state',
            'country',
            'pin')
        ->first();
    }

    public function getsiblingDetails($parent_id, $studentId)
    {
        return Student::whereNot('students.id', $studentId)
        ->where('students.parent_id', $parent_id)
        ->leftJoin('user_details', 'user_details.user_id', '=', 'students.user_id')
        ->leftJoin('classes', 'classes.id', '=', 'students.class_id')
        ->leftJoin('sections', 'sections.id', '=', 'students.section_id')
        ->leftJoin('medium', 'medium.id', '=', 'students.medium_id')
        ->leftJoin('groups', 'groups.id', '=', 'students.group_id')
        ->leftJoin('languages as first_lang', 'first_lang.id', '=', 'students.first_lang_id')
        ->leftJoin('languages as second_lang', 'second_lang.id', '=', 'students.second_lang_id')
        ->leftJoin('languages as third_lang', 'third_lang.id', '=', 'students.third_lang_id')
        ->select(
            'students.roll_no',
            'students.first_name',
            'students.middle_name',
            'students.last_name',
            'classes.name as class_name', 
            'sections.name as section_name', 
            'medium.name as medium_name',
            'groups.name as group_name',
            'students.fee_book_no',
            'students.place_of_birth',
            'students.mother_tongue',
            'students.physically_challenge',
            'students.neet_applicable',
            'students.transport_required',
            'students.reg_no',
            'students.emis_no',
            'students.cse_no',
            'students.file_no',
            'students.admission_no',
            'students.application_no',
            'students.admission_date',
            'students.joining_quota',
            'first_lang.name as first_language',
            'second_lang.name as second_language',
            'third_lang.name as third_language',
            'students.achievements',
            'students.area_of_interest',
            'students.additional_skills',
            'students.image',
            'user_details.date_of_birth',
            'user_details.gender',
            'user_details.blood_group',
            'user_details.religion',
            'user_details.cast',
            'user_details.image',
            'user_details.mother_tongue',
            'user_details.aadhaar_card_no',
            'user_details.pan_card_no')
        ->get();
    }
}

