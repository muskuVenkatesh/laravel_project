<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeesTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string',
            'status' => 'sometimes|integer|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'name.sometimes' => 'The name field is optional.',
            'name.string' => 'The name field must be a string.',
            'status.sometimes' => 'The status field is optional.',
            'status.integer' => 'The status field must be an integer.',
            'status.in' => 'The status field must be either 0 or 1.',
        ];
    }
}
