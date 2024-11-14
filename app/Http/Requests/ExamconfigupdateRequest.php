<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamconfigupdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'max_marks' => 'required',
            'pass_marks' => 'required',
            'sequence' => 'required',
            'is_grade' => 'required',
            'topper_visible'=> 'required',
            'rank_visible'=> 'required',
            'lock_report' => 'nullable',
            'is_average' => 'required',
            'add_in_grand' => 'required',
            'internal' => 'nullable',
            'external' => 'nullable',
        ];
    }
}
