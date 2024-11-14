<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QualificationCreateRequest extends FormRequest
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
            'name' => 'required|string|max:150|unique:qualifications,name',
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
            'name.required' => 'The Qualification Name field is required.',
            'name.string' => 'The Qualification Name must be a string.',
            'name.max' => 'The Qualification Name may not be greater than 150 characters.',
        ];
    }
}
