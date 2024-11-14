<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeWorkRequest extends FormRequest
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
            'work_data' => 'required',
            'date' => 'required',
            'section_id' => 'nullable|present',
            'homework_type' => 'nullable|present',
            'notification' =>'nullable|present',
            'notification_type' =>'nullable|present',
            'special_instruction_student' =>'nullable|present',
            'special_instruction_message' =>'nullable|present',
            'indivedual_student' =>'nullable|present',
            'indivedual_message' =>'nullable|present',
        ];
    }
}
