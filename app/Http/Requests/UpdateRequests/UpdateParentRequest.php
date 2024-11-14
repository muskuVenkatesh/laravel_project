<?php

namespace App\Http\Requests\UpdateRequests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateParentRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'date_of_birth' => 'required',
            'gender' => 'required|string',
            'blood_group' => 'nullable|string',
            'religion' => 'nullable|string|max:255',
            'cast' => 'nullable|string|max:255',
            'image' => 'required|file|mimes:jpeg,png,jpg',
            'mother_tounge' => 'nullable|string|max:255',
            'aadhaar_card_no' => 'required|string|max:12',
            'pan_card_no' => 'required|string|max:12',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'pin' => 'required|digits:6',
            'tmp_address' => 'nullable|string|max:255',
            'temp_city' => 'nullable|string|max:255',
            'temp_state' => 'nullable|string|max:255',
            'temp_pin' => 'nullable|digits:6',
            'temp_country' => 'nullable|string|max:255',
            'student_id' => 'nullable|exists:students,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|integer|min:10',
            'alt_phone' => 'required|string|max:10',
            'alt_email' => 'required|string|max:100',
            'education' => 'required|exists:qualifications,id',
            'occupation' => 'required|exists:occupations,id',
            'annual_income' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:15',
            'mother_email' => 'required|string|email|max:255',
            'mother_education' => 'required|exists:qualifications,id',
            'mother_occupation' => 'required|exists:occupations,id',
            'mother_annual_income' => 'required|string|max:255',
            'mother_aadhaar_no' => 'required|string|max:12',
            'mother_pan_card' => 'required|string|max:10',
            'mother_dob' => 'required'
        ];
    }
}
