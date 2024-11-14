<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeesAcademicRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'academic_id' => 'required|integer',
            'fee_type' => 'required|integer',
            'fees_amount' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'academic_id.required' => 'The academic ID field is required.',
            'academic_id.integer' => 'The academic ID must be an integer.',
            'fee_type.required' => 'The fee type field is required.',
            'fee_type.integer' => 'The fee type must be an integer.',
            'fees_amount.required' => 'The fees amount field is required.',
            'fees_amount.numeric' => 'The fees amount must be a number.',
        ];
    }
}
