<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamconfigRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'exam_id' => 'required|integer',
            'class_id' => 'required|integer',
            'section_id' => 'required',
            'subjects' => 'required',
            'sequence' => 'nullable',
            'is_grade' => 'nullable',
            'topper_visible'=> 'nullable',
            'rank_visible'=> 'nullable',
            'lock_report' => 'nullable'
        ];
    }
}
