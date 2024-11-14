<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificateRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'certificate_data' => 'required|array', 
            'certificate_data.ref_no' => 'required|string|max:255',
            'certificate_data.student_name' => 'required|string|max:255',
            'certificate_data.adm_no' => 'required|string|max:255',
            'certificate_data.father_name' => 'required|string|max:255',
            'certificate_data.mother_name' => 'required|string|max:255',
            'certificate_data.class' => 'required|string|max:255',
            'certificate_data.admission_date' => 'required|date_format:Y-m-d', 
            'certificate_data.school_name' => 'required|string|max:255',
            'certificate_data.academic_session' => 'required|string|max:255',
            'certificate_data.dob' => 'required|date_format:Y-m-d',
            'certificate_data.issue_date' => 'required|date_format:Y-m-d', 
            'certificate_data.principal_name' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'certificate_data.required' => 'The certificate data is required.',
            'certificate_data.array' => 'The certificate data must be an array.',
            'certificate_data.ref_no.required' => 'The reference number is required.',
            'certificate_data.student_name.required' => 'The student name is required.',
            'certificate_data.adm_no.required' => 'The admission number is required.',
            'certificate_data.father_name.required' => 'The father\'s name is required.',
            'certificate_data.mother_name.required' => 'The mother\'s name is required.',
            'certificate_data.class.required' => 'The class is required.',
            'certificate_data.admission_date.required' => 'The admission date is required.',
            'certificate_data.school_name.required' => 'The school name is required.',
            'certificate_data.academic_session.required' => 'The academic session is required.',
            'certificate_data.dob.required' => 'The date of birth is required.',
            'certificate_data.issue_date.required' => 'The issue date is required.',
            'certificate_data.principal_name.required' => 'The principal name is required.',
        ];
    }
}
