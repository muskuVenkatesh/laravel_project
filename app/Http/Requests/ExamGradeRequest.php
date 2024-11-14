<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust this based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'required|integer', 
            'class_id' => 'required|integer',  
            'max_marks' => 'required|integer', 
            'min_marks' => 'required|integer', 
            'name' => 'required|string|max:255', 
       
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'The branch ID is required.',
            'class_id.required' => 'The class ID is required.',
            'max_marks.required' => 'The maximum marks are required.',
            'min_marks.required' => 'The minimum marks are required.',
            'name.required' => 'The name is required.',
            'status.required' => 'The status is required.',
            'status.in' => 'The status must be either 1 (active) or 0 (inactive).',
        ];
    }
}
