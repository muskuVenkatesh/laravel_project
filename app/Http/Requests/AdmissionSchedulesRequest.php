<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionSchedulesRequest extends FormRequest
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
            'enquiry_id' => 'required',
            'venue' => 'nullable',
            'interview_date' => 'nullable',
            'comments' => 'nullable',
            'schedule_status' => 'required'
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'admission_id.required' => 'Admission ID is required.',
            'admission_id.integer' => 'Admission ID must be an integer.',
            'admission_id.exists' => 'The provided Admission ID does not exist.',
            'venue.required' => 'Venue is required.',
            'venue.string' => 'Venue must be a string.',
            'venue.max' => 'Venue may not be greater than 255 characters.',
            'interview_date.required' => 'Interview date is required.',
            'interview_date.date_format' => 'Interview date must be in the format Y-m-d H:i:s.',
            'comments.string' => 'Comments must be a string.',
            'schedule_status.required' => 'Schedule status is required.',
            'schedule_status.in' => 'Schedule status must be either 0 or 1.',
        ];
    }
}
