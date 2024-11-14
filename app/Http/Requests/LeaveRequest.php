<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
{
 
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'from_date' => 'required',
            'student_id' => 'required',
            'to_date' => 'required|date|after_or_equal:from_date', 
            'reason' => 'required|string|max:255',
        ];
    }
}
