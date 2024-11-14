<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LibBookIssueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Set to true if authorization is not needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        'book_id' => 'required|array',
        'book_id.*' => 'required|exists:lib_book,id',
        'staff_user_id' => 'nullable|exists:users,id',
        'student_id' => 'required|exists:students,id',
        'issue_date' => 'required'
    ];
    }
}
