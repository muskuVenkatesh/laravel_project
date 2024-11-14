<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemarksRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:500|unique:report_remarks,name',
            'remarks_by' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
           'name.required' => 'The remark name is required.',
            'name.string' => 'The remark name must be a valid string.',
            'name.max' => 'The remark name cannot be longer than 500 characters.',
            'remarks_by.required' => 'The remarks_by field is required.',
            'remarks_by.string' => 'The remarks_by field must be a valid string.',
            'name.unique' => 'Remark Already exist'
        ];
    }
}
