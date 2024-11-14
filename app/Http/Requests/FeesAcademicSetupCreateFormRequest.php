<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeesAcademicSetupCreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Update according to your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,
     */
    public function rules()
    {
        return [
            'school_id' => 'required',
            'branch_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'template_id' => 'nullable',
            'academic_id' => 'required',
            'parent_recipet' => 'nullable',
            'amount' => 'required',
            'discount' => 'nullable',
            'discount_type'=>'nullable',
            'pay_timeline' => 'required',
            'pay_timeline_date' => 'required',
        ];
    }
}
