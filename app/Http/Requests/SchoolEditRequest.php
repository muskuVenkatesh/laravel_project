<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolEditRequest extends FormRequest
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
            'name' => 'required|string|max:150',
            'school_code'=> 'sometimes|required|string|max:150',
            'address' => 'required|string|max:150',
            'city' => 'required|string|max:150',
            'dist' => 'required|string|max:150',
            'state'=> 'required|string|max:150',
            'pin' => 'required|integer',

            'branch_code' =>  'required|string',
            'branch_name' =>  'required|string',
            'branch_address' => 'required|string|max:150',
            'branch_city' => 'required|string|max:150',
            'branch_dist' => 'required|string|max:150',
            'branch_state'=> 'required|string|max:150',
            'branch_pin' => 'required|integer',
            'branch_phone' => 'required|integer',
            'branch_email' =>  'required|email',

            'stud_grade'=> 'nullable|string|max:255|present',
            'reg_start_from'=> 'nullable|string|max:255|present',
            'reg_prefix_digit'=> 'nullable|string|max:255|present',
            'fees_due_days'=> 'nullable|string|max:255|present',
            'cal_fees_fine'=> 'nullable|string|max:255|present',
            'offline_payments'=> 'nullable',

            'period_attendance' =>'nullable|present',
            'subject_select' =>'nullable|present',
            'print_file' => 'nullable',
            'report_card' => 'nullable',
            'logo_file' => 'nullable',
            'text_logo' => 'nullable',
            'personality_traits' => 'nullable',
            'report_card_template' => 'nullable',
            'receipt_template' => 'nullable',
            'id_card_template' => 'nullable'

        ];
    }

    public function messages()
    {
        return [

            //School validation
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 150 characters.',
            'school_code.required' => 'The school code field is required.',
            'school_code.string' => 'The school code must be a string.',
            'school_code.max' => 'The school code may not be greater than 150 characters.',
            'address.required' => 'The address field is required.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 150 characters.',
            'city.required' => 'The city field is required.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city may not be greater than 150 characters.',
            'dist.required' => 'The district field is required.',
            'dist.string' => 'The district must be a string.',
            'dist.max' => 'The district may not be greater than 150 characters.',
            'state.required' => 'The state field is required.',
            'state.string' => 'The state must be a string.',
            'state.max' => 'The state may not be greater than 150 characters.',
            'pin.required' => 'The pin field is required.',
            'pin.string' => 'The pin must be a integer.',

            //Branch Validation
            // 'branch_name.required' => 'The Branch Name field is required.',
            // 'branch_name.string' => 'The Branch Name must be a string.',
            // 'branch_name.max' => 'The Branch Name may not be greater than 150 characters.',
            // 'branch_code.required' => 'The Branch code field is required.',
            // 'branch_code.string' => 'The Branch code must be a string.',
            // 'branch_code.max' => 'The Branch code may not be greater than 150 characters.',
            // 'branch_address.required' => 'The address field is required.',
            // 'branch_address.string' => 'The address must be a string.',
            // 'branch_address.max' => 'The address may not be greater than 150 characters.',
            // 'branch_city.required' => 'The city field is required.',
            // 'branch_city.string' => 'The city must be a string.',
            // 'branch_city.max' => 'The city may not be greater than 150 characters.',
            // 'branch_dist.required' => 'The district field is required.',
            // 'branch_dist.string' => 'The district must be a string.',
            // 'branch_dist.max' => 'The district may not be greater than 150 characters.',
            // 'branch_state.required' => 'The state field is required.',
            // 'branch_state.string' => 'The state must be a string.',
            // 'branch_state.max' => 'The state may not be greater than 150 characters.',
            // 'branch_pin.required' => 'The pin field is required.',
            // 'branch_pin.string' => 'The pin must be a integer.',
        ];
    }
}
