<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonalityTraitRequest extends FormRequest
{

    public function authorize()
    {
        return true; 
    }

   
    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'branch_id' => 'sometimes|required|integer|exists:branches,id',
            'sequence_id' => 'sometimes|required|integer',
        
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required when provided.',
            'branch_id.required' => 'The branch ID is required when provided.',
            'branch_id.exists' => 'The selected branch does not exist.',
            'sequence_id.required' => 'The sequence ID is required when provided.',
           
        ];
    }
}

