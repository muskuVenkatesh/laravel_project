<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
        // Student Validation Rules,
        'branch_id' => 'required|integer|exists:branches,id',
        'academic_year_id' => 'required|integer|exists:academic_details,id',
        'roll_no' => 'nullable|string|max:255|present',
        'stfirst_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'stlast_name' => 'required|string|max:255',
        'fee_book_no' => 'nullable|string|max:255|present',
        'place_of_birth' => 'nullable|string|max:255|present',
        'mother_tongue' => 'nullable|max:255|present',
        'physically_challenge' => 'nullable|string|max:255|present',
        'neet_applicable' => 'nullable|string|in:yes,no|present',
        'transport_required' => 'nullable|string|in:yes,no|present',
        'medium_id' => 'nullable|integer|exists:medium,id|present',
        'class_id' => 'nullable|integer|exists:classes,id|present',
        'section_id' => 'nullable|integer|exists:sections,id|present',
        'group_id' => 'nullable|integer|exists:groups,id|present',
        'reg_no' => 'nullable|string|max:255|present',
        'emis_no' => 'nullable|string|max:255|present',
        'cse_no' => 'nullable|string|max:255|present',
        'file_no' => 'nullable|string|max:255|present',
        'admission_no' => 'nullable|string|max:255|present',
        'admission_date' => 'required|date|present',
        'application_no' => 'nullable|string|max:255|present',
        'joining_quota' => 'nullable|string|max:255|present',
        'first_lang_id' => 'nullable|integer|exists:languages,id|present',
        'second_lang_id' => 'nullable|integer|exists:languages,id|present',
        'third_lang_id' => 'nullable|integer|exists:languages,id|present',
        'achievements' => 'nullable|string|max:255',
        'area_of_interest' => 'nullable|string|max:255|present',
        'additional_skills' => 'nullable|string|max:255|present',



        //Parents validation
        'pfirst_name' => 'nullable|string|max:255|present',
        'pmiddle_name' => 'nullable|string|max:255|present',
        'plast_name' => 'nullable|string|max:255|present',
        'phone' => 'required|string|max:255|present',
        'email' =>'required|string|email|max:255',
        'date_of_birth' => 'nullable|present',
        'gender' => 'nullable|present',
        'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048|present',
        'aadhaar_card_no' => 'required|string|max:20|unique:user_details,aadhaar_card_no',
        'pan_card_no' => 'required|string|max:20|unique:user_details,pan_card_no',

        //Student User Details
        'stdate_of_birth' => 'nullable||present',
        'stgender' => 'nullable|present',
        'stblood_group' => 'nullable|string|max:10|present',
        'streligion' => 'nullable|string|max:255|present',
        'stcast' => 'nullable|string|max:255|present',
        'stimage' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        'staadhaar_card_no' => 'nullable|string|max:20|present',
        'stpan_card_no' => 'nullable|string|max:20|present',
        'staddress' => 'nullable|string|max:255|present',
        'stcity' => 'nullable|string|max:255|present',
        'ststate' => 'nullable|string|max:255|present',
        'stcountry' => 'nullable|string|max:255|present',
        'stpin' => 'nullable|integer|present',
        'sttmp_address' => 'nullable|string|max:255|present',
        'sttemp_city' => 'nullable|string|max:255|present',
        'sttemp_state' => 'nullable|string|max:255|present',
        'sttemp_pin' => 'nullable|integer|present',
        'sttemp_country' => 'nullable|string|max:255|present',
        ];
    }


    public function messages()
    {
        return [
            'user_id.required' => 'The user ID field is required.',
            'user_id.integer' => 'The user ID must be an integer.',
            'user_id.exists' => 'The selected user ID is invalid.',
            'branch_id.required' => 'The branch ID field is required.',
            'branch_id.integer' => 'The branch ID must be an integer.',
            'branch_id.exists' => 'The selected branch ID is invalid.',
            'parent_id.required' => 'The parent ID field is required.',
            'parent_id.integer' => 'The parent ID must be an integer.',
            'parent_id.exists' => 'The selected parent ID is invalid.',
            'academic_year_id.required' => 'The academic year ID field is required.',
            'academic_year_id.integer' => 'The academic year ID must be an integer.',
            'academic_year_id.exists' => 'The selected academic year ID is invalid.',
            'first_name.required' => 'The first name field is required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The first name may not be greater than 255 characters.',
            'last_name.required' => 'The last name field is required.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name may not be greater than 255 characters.',
            'medium_id.required' => 'The medium ID field is required.',
            'medium_id.integer' => 'The medium ID must be an integer.',
            'medium_id.exists' => 'The selected medium ID is invalid.',
            'class_id.required' => 'The class ID field is required.',
            'class_id.integer' => 'The class ID must be an integer.',
            'class_id.exists' => 'The selected class ID is invalid.',
            'section_id.required' => 'The section ID field is required.',
            'section_id.integer' => 'The section ID must be an integer.',
            'section_id.exists' => 'The selected section ID is invalid.',
            'group_id.required' => 'The group ID field is required.',
            'group_id.integer' => 'The group ID must be an integer.',
            'group_id.exists' => 'The selected group ID is invalid.',
            'first_lang_id.required' => 'The first language ID field is required.',
            'first_lang_id.integer' => 'The first language ID must be an integer.',
            'first_lang_id.exists' => 'The selected first language ID is invalid.',
            'second_lang_id.required' => 'The second language ID field is required.',
            'second_lang_id.integer' => 'The second language ID must be an integer.',
            'second_lang_id.exists' => 'The selected second language ID is invalid.',
            'third_lang_id.required' => 'The third language ID field is required.',
            'third_lang_id.integer' => 'The third language ID must be an integer.',
            'third_lang_id.exists' => 'The selected third language ID is invalid.',
            'achievements.binary' => 'The achievements must be a binary data.',
            'achievements.max' => 'The achievements may not be greater than 255 characters.',
            'area_of_interest.string' => 'The area of interest must be a string.',
            'area_of_interest.max' => 'The area of interest may not be greater than 255 characters.',
            'additional_skills.string' => 'The additional skills must be a string.',
            'additional_skills.max' => 'The additional skills may not be greater than 255 characters.',
            'reason_gap.string' => 'The reason for the gap must be a string.',
            'reason_gap.max' => 'The reason for the gap may not be greater than 255 characters.',
            'image.string' => 'The image must be a string.',
            'image.max' => 'The image may not be greater than 255 characters.',
        ];
    }
}
