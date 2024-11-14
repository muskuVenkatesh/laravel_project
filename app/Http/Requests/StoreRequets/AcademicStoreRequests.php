<?php

namespace App\Http\Requests\StoreRequets;

use Illuminate\Foundation\Http\FormRequest;

class AcademicStoreRequests extends FormRequest
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
            'academic_years' => 'required|date_format:Y',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'academic_description' => 'required|string|max:255',

        ];
    }

    public function messages()
    {
        return [
            'end_date.after' => 'The end date must be a date after the start date.', 
        ];
    }
}
