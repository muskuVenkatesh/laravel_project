<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamMarksUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'branch_id' => 'required|integer',
            'student_id' =>'required',
            'students' => 'required'
        ];
    }
}
