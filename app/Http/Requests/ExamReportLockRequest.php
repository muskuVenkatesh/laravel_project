<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamReportLockRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }
    public function rules()
    {
        return [
            'name' => 'required|string|max:255', 
            'value' => 'required|string|max:255', 
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'value.required' => 'The value field is required.',   
        ];
    }
}
