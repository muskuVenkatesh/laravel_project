<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookReturnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'return_staff_user_id' => 'nullable|exists:users,id',
            'return_status' => 'required|in:0,2,3,4',
            'return_date' => 'required',
            'return_comments' => 'nullable|string',
        ];
    }

}