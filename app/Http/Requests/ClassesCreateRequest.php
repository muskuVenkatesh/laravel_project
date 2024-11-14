<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassesCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Update according to your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:150',
            'branch_id' => 'required',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'name.required' => 'The Class Name field is required.',
            'name.string' => 'The Class Name must be a string.',
            'name.max' => 'The Class Name may not be greater than 150 characters.',

            'branch_id.required' => 'The Branch Name field is required.',
        ];
    }
}
