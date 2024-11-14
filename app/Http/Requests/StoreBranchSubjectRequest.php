<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchSubjectRequest extends FormRequest
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
    public function rules()
    {
        return [
            'branch_id' => 'required|integer|exists:branches,id',
            'subjects' => 'required',
            'section_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'branch_id.required' => 'The branch ID is required.',
            'branch_id.exists' => 'The selected branch ID does not exist.',
            'subject_id.required' => 'The subject ID is required.',
            'subject_id.exists' => 'The selected subject ID does not exist.',
        ];
    }
}
