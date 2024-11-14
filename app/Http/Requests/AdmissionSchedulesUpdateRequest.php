<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionSchedulesUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enquiry_id' => 'required',
            'venue' => 'nullable',
            'interview_date' => 'nullable',
            'comments' => 'nullable',
            'schedule_status' => 'required'
        ];
    }
}
