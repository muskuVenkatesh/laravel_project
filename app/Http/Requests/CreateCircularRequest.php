<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCircularRequest extends FormRequest
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
    public function rules()
    {
        return [
            'homework_id' => 'nullable|string',
            'student_id' => 'nullable|string',
            'circular_type' => 'required|array',
            'circular_type.*' => 'required|string',
            'notification_type' => 'required|array',
            'notification_type.*' => 'integer|exists:notification_types,id',
            'message' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048',
        ];
    }
}
