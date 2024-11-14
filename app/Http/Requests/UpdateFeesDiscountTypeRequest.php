<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeesDiscountTypeRequest extends FormRequest
{
    public function authorize()
    {

        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'amount' => 'required',
            'status' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'status.boolean' => 'The status must be 0 or 1.',
        ];
    }
}
