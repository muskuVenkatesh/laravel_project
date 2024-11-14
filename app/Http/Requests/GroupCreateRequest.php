<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupCreateRequest extends FormRequest
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
     * @return array<string,
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:150|unique:groups,name',
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
            'name.required' => 'The Group Name field is required.',
            'name.string' => 'The Group Name must be a string.',
            'name.max' => 'The Group Name may not be greater than 150 characters.',
            'name.unique' => 'Group name is already exist'
        ];
    }
}
