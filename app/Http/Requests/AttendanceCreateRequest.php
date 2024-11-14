<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceCreateRequest extends FormRequest
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
            'branch_id' => 'required',
            'class_id' => 'required',
            'subject_id' => 'nullable|present',
            'attendance' => 'required',
            'date' => 'required',
            'notification' =>'nullable|present',
            'notification_type' =>'nullable|present',
            'otp_token' => 'required',
            'otp' => 'nullable|present'
        ];
    }
}
