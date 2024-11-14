<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediumEditRequest extends FormRequest
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
            'name' => 'required|string|max:150|unique:medium,name',
            'branch_id' => 'nullable',
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
            'name.required' => 'The Medium Name field is required.',
            'name.string' => 'The Medium Name must be a string.',
            'name.max' => 'The Medium Name may not be greater than 150 characters.',
            'name.unique' => 'Medium already exist'
        ];
    }
}
