<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_id' => 'required|integer|exists:branches,id',
            'academic_year_id' => 'required|integer',
            'roll_no' => 'required|string',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string|max:255|present',
            'last_name' => 'required|string',
            'fee_book_no' => 'nullable|string|max:255|present',
            'place_of_birth' => 'nullable|string|max:255|present',
            'mother_tongue' => 'nullable|string|max:255|present',
            'physically_challenge' => 'nullable|string|max:255|present',
            'neet_applicable' => 'nullable|string|max:255|present',
            'transport_required' => 'nullable|string|max:255|present',
            'medium_id' => 'required|integer|exists:medium,id',
            'class_id' => 'required|integer|exists:class,id',
            'section_id' => 'required|integer|exists:section,id',
            'group_id' => 'nullable|integer|present',
            'reg_no' => 'nullable|string|max:255|present',
            'emis_no' => 'nullable|string|max:255|present',
            'cse_no' => 'nullable|string|max:255|present',
            'file_no' => 'nullable|string|max:255|present',
            'admission_no' => 'nullable|string|max:255|present',
            'application_no' => 'nullable|string|max:255|present',
            'joining_quota' => 'nullable|string|max:255|present',
            'first_lang_id' => 'nullable|integer|exists:languages,id',
            'second_lang_id' => 'nullable|integer|exists:languages,id',
            'third_lang_id' => 'nullable|integer|exists:languages,id',
            'achievements' => 'nullable|binary|present',
            'area_of_interest' => 'nullable|string|max:255|present',
            'additional_skills' => 'nullable|string|max:255|present',
            'image' => 'nullable|string|max:255|present',
        ];
    }
}
