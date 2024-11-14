<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionAnnouncementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your auth logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'branch_id' => 'required',
            'academic_year_id' => 'required',
            'application_fee' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'last_submission_date' => 'required',
            'announcement_data' => 'required'
        ];
    }
}
