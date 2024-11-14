<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionUpdateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'announcement_id' => 'required',
            'application_type' => 'required',
            'branch_id' => 'required|integer',
            'academic_year_id' => 'required|integer',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|present|present',
            'last_name' => 'required|string|max:255',
            'fee_book_no' => 'nullable|present|present',
            'place_of_birth' => 'nullable|present|present',
            'mother_tongue' => 'nullable|present|present',
            'physically_challenge' => 'nullable|present|present',
            'neet_applicable' => 'nullable|present',
            'transport_required' => 'nullable|present',
            'class_id' => 'required|integer',
            'reg_no' => 'nullable|present|present',
            'emis_no' => 'nullable|present|present',
            'cse_no' => 'nullable|present|present',
            'file_no' => 'nullable|present|present',
            'admission_no' => 'nullable|present|present',
            'admission_fee' => 'nullable|present',
            'admission_status' => 'nullable|present|present',
            'application_no' => 'nullable|present|present',
            'application_fee' => 'nullable|present',
            'application_status' => 'nullable|present|present',
            'admission_date' => 'required',
            'joining_quota' => 'nullable|present|present',
            'first_lang_id' => 'nullable|present',
            'second_lang_id' => 'nullable|present',
            'third_lang_id' => 'nullable|present',
            'area_of_interest' => 'nullable|present',
            'additional_skills' => 'nullable|present',
            'previous_school' => 'nullable|present|present',
            'last_study_course' => 'nullable|present|present',
            'last_exam_marks' => 'nullable|present|present',
            'reason_change' => 'nullable|present',
            'reason_gap' => 'nullable|present',
            'date_of_birth' => 'required',
            'gender' => 'required|string|max:255',
            'blood_group' => 'nullable|present',
            'religion' => 'nullable|present|present',
            'cast' => 'nullable|present|present',
            'image' => 'nullable|present|present',
            'nationality' => 'nullable|present|present',
            'mother_tounge' => 'nullable|present|present',
            'addhar_card_no' => 'nullable|present|present',
            'pan_card_no' => 'nullable|present|present',
            'address' => 'nullable|present',
            'city' => 'nullable|present|present',
            'state' => 'nullable|present|present',
            'country' => 'nullable|present|present',
            'pin' => 'nullable|present',
            'payment_status' => 'nullable|present',
            'extra_curricular_activites' => 'nullable|present',
            'school_enquiry' => 'nullable|present|present',
            'hostel_required' => 'nullable|present',
            'identification_mark' => 'nullable|present|present',
            'identification_mark_two' => 'nullable|present|present',
            'sports' => 'nullable|present|present',
            'achievements' => 'nullable|present|present',
            'volunteer' => 'nullable|present|present',
            'quota' => 'nullable|present|present',

            //Admission Details
            'father_name' => 'required|string|max:255',
            'middle_name' => 'nullable|present',
            'father_last_name' => 'required|string|max:255',
            'phone' => 'required',
            'father_phone' => 'nullable|present',
            'father_email' => 'nullable|present',
            'father_education' => 'nullable|present',
            'father_occupation' => 'nullable|present',
            'annual_income' => 'nullable|present',
            'father_aadhaar_no' => 'nullable|present',
            'father_pan_card' => 'nullable|present',
            'mother_name' => 'nullable|present',
            'mother_phone' => 'nullable|present',
            'mother_email' => 'nullable|present',
            'mother_education' => 'nullable|present',
            'mother_occupation' => 'nullable|present',
            'mother_annual_income' => 'nullable|present',
            'mother_aadhaar_no' => 'nullable|present',
            'mother_pan_card' => 'nullable|present',
        ];
    }
}
