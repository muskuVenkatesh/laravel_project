<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionAnnouncementUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|integer',
            'application_fee' => 'required|string|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
            'last_submission_date' => 'required|date',
            'class' => 'required|integer',
            'admission_fees' => 'required|string|max:255',
            'quota' => 'nullable|string|max:255',
            'seats_available' => 'required|integer',
            'exam_required' => 'required',
        ];
    }
}
