<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectsEditRequest extends FormRequest
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
        $subjectId = $this->route('id');
        return [
            'name' => 'required|string|max:150|unique:subjects,name,' . $subjectId,
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
            'name.required' => 'The Subject Name field is required.',
            'name.unique' => 'The subject name already exists. Please choose a different name.',
            'name.string' => 'The Subject Name must be a string.',
            'name.max' => 'The Subject Name may not be greater than 150 characters.',
            'type.required' => 'The Subject Type field is required.',
            'type.max' => 'The Subject Type may not be greater than 150 characters.',
        ];
    }
}
