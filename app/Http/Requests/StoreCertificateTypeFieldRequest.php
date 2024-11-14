<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateTypeFieldRequest extends FormRequest
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
            'certificate_type_id' => 'required|exists:certificate_types,id',
            'field_label' => 'required|array',
            'field_name' => 'nullable|array',
            'field_type' => 'required|array',
            'field_label.*' => 'nullable|string|max:255',
            'field_name.*' => 'nullable|string|max:255',
            'field_type.*' => 'required|string|max:255',
        ];

    }
}
