<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            // Student Validation Rules
            'branch_id' => 'required|integer|exists:branches,id',
            'academic_year_id' => 'required|integer|exists:academic_details,id|present',
            'roll_no' => 'nullable|string|max:255|present',
            'first_name' => 'required|string|max:255|present',
            'middle_name' => 'nullable|string|max:255|present',
            'last_name' => 'required|string|max:255|present',
            'fee_book_no' => 'nullable|string|max:255|present',
            'place_of_birth' => 'nullable|string|max:255|present',
            'mother_tongue' => 'nullable|string|max:255|present',
            'physically_challenge' => 'nullable|string|max:255|present',
            'neet_applicable' => 'nullable|string|in:yes,no|present',
            'transport_required' => 'nullable|string|in:yes,no|present',
            'medium_id' => 'required|integer|exists:medium,id',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'required|integer|exists:sections,id',
            'group_id' => 'required|integer|exists:groups,id',
            'reg_no' => 'nullable|string|max:255|present',
            'emis_no' => 'nullable|string|max:255|present',
            'cse_no' => 'nullable|string|max:255|present',
            'file_no' => 'nullable|string|max:255|present',
            'admission_no' => 'nullable|string|max:255|present',
            'admission_date' => 'required',
            'application_no' => 'nullable|string|max:255|present',
            'joining_quota' => 'nullable|string|max:255|present',
            'first_lang_id' => 'nullable|integer|exists:languages,id|present',
            'second_lang_id' => 'nullable|integer|exists:languages,id|present',
            'third_lang_id' => 'nullable|integer|exists:languages,id|present',
            'achievements' => 'nullable|file|mimes:txt,pdf,jpeg,png|max:2048',
            'area_of_interest' => 'nullable|string|max:255|present',
            'additional_skills' => 'nullable|string|max:255|present',
            'image' => 'nullable|file|mimes:jpeg,png|max:2048|present',

            // User Details Validation Rules
            'date_of_birth' => 'nullable|present',
            'gender' => 'nullable|string|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'religion' => 'nullable|string|max:255|present',
            'cast' => 'nullable|string|max:255|present',
            'mother_tongue' => 'nullable|max:255|present',
            'aadhaar_card_no' => 'nullable|string|max:20|present',
            'pan_card_no' => 'nullable|string|max:20|present',
            'address' => 'nullable|string|max:255|present',
            'city' => 'nullable|string|max:255|present',
            'state' => 'nullable|max:255|present',
            'country' => 'nullable|string|max:255|present',
            'pin' => 'nullable|integer',
            'tmp_address' => 'nullable|string|max:255|present',
            'temp_city' => 'nullable|string|max:255|present',
            'temp_state' => 'nullable|max:255|present',
            'temp_pin' => 'nullable|integer|present',
            'temp_country' => 'nullable|string|max:255|present',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'user_id.integer' => 'The user ID must be an integer.',
            'user_id.exists' => 'The selected user ID is invalid.',
            'branch_id.integer' => 'The branch ID must be an integer.',
            'branch_id.exists' => 'The selected branch ID is invalid.',
            'parent_id.integer' => 'The parent ID must be an integer.',
            'parent_id.exists' => 'The selected parent ID is invalid.',
            'academic_year_id.integer' => 'The academic year ID must be an integer.',
            'academic_year_id.exists' => 'The selected academic year ID is invalid.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The first name may not be greater than 255 characters.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name may not be greater than 255 characters.',
            'neet_applicable.in' => 'The NEET applicable field must be either "yes" or "no".',
            'transport_required.in' => 'The transport required field must be either "yes" or "no".',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg.',
            'image.max' => 'The image may not be greater than 2048 kilobytes.',
            'status.in' => 'The status must be either "active" or "inactive".',
        ];
    }
    }

