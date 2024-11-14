<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionEnquiryCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ensure the request is always authorized, modify if necessary
    }

    public function rules()
    {
        return [
            'announcement_id'   => 'required|integer',
            'application_no'    => 'required|integer',
            'application_fee'   => 'required',
            'name'              => 'required|string|max:255',
            'father_name'       => 'required|string|max:255',
            'contact_no'        => 'required|string|max:20',
            'email'             => 'required|email|max:255',
            'class_applied'     => 'required|integer',
            'dob'               => 'required',
            'assesment_date'    => 'nullable|present',
            'second_language'   => 'nullable|integer|present',
            'course_type'       => 'required|integer',
            'payment_mode'      => 'required|string|max:255',
        ];
    }
}
