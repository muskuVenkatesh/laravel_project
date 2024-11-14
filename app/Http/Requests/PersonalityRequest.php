<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonalityRequest extends FormRequest
{
 
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'branch_id' => 'required|integer|exists:branches,id',
            'sequence_id' => 'required|integer',  
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'branch_id.required' => 'The branch ID is required.',
            'branch_id.exists' => 'The selected branch does not exist.',
            'sequence_id.required' => 'The sequence ID is required.',
        ];
    }
}
