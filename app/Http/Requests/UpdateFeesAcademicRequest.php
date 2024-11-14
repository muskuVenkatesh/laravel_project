<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeesAcademicRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'academic_id' => 'sometimes|integer',
            'fee_type' => 'sometimes|integer',
            'fees_amount' => 'sometimes|numeric',
            'status' => 'nullable|present', 
        ];
    }

    public function messages()
    {
        return [
            'academic_id.integer' => 'The academic ID must be an integer.',
            'fee_type.integer' => 'The fee type must be an integer.',
            'fees_amount.numeric' => 'The fees amount must be a number.',
            'status.boolean' => 'The status must be 0 or 1.',
        ];
    }
}
