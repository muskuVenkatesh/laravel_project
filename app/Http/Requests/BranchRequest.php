<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Update according to your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,
     */
    public function rules()
    {
        return [
            'school_id' => 'required|integer|exists:schools,id',
            'academic_id' => 'required|integer|exists:academic_details,id',
            'branch_name' => 'required|string|max:150',
            'branch_address' => 'required|string|max:150',
            'branch_code' => 'required|string|max:50',
            'branch_dist' => 'required|string|max:150',
            'branch_city' => 'required|string|max:150',
            'branch_state' => 'required|string|max:150',
            'branch_pin' => 'required|integer|digits:6',
            'branch_phone' => 'required|numeric|digits_between:10,15',
            'branch_email' => 'required|email|max:255',

            'period_attendance' => 'nullable|boolean',
            'subject_select' => 'nullable|boolean',
            'print_file' => 'required|mimes:jpeg,png,jpg,gif',
            'report_card' => 'required|mimes:jpeg,png,jpg,gif',
            'logo_file' => 'required|mimes:jpeg,png,jpg,gif',
            'text_logo' => 'required|mimes:jpeg,png,jpg,gif',
            'report_card_template' => 'nullable',
            'receipt_template' => 'nullable',
            'id_card_template' => 'nullable'

            // 'stud_grade'=> 'required|string|max:150',
            // 'stud_username_prefix'=> 'required|string|max:150',
            // 'stud_default_password'=> 'required|string|max:150',
            // 'grd_username_prefix'=> 'required|string|max:150',
            // 'grd_default_password'=> 'required|string|max:150',
            // 'currency'=> 'required',
            // 'reg_start_from'=> 'required|integer',
            // 'reg_prefix_digit'=> 'required|integer',
            // 'fees_due_days'=> 'required',
            // 'cal_fees_fine'=> 'required',
            // 'offline_payments'=> 'nullable',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'branch_name.required' => 'The Branch Name field is required.',
            'branch_name.string' => 'The Branch Name must be a string.',
            'branch_name.max' => 'The Branch Name may not be greater than 150 characters.',
            'branch_address.required' => 'The Address field is required.',
            'branch_address.string' => 'The Address must be a string.',
            'branch_address.max' => 'The Address may not be greater than 150 characters.',
            'branch_code.required' => 'The Branch Code field is required.',
            'branch_code.string' => 'The Branch Code must be a string.',
            'branch_code.max' => 'The Branch Code may not be greater than 50 characters.',
            'branch_dist.required' => 'The District field is required.',
            'branch_dist.string' => 'The District must be a string.',
            'branch_dist.max' => 'The District may not be greater than 150 characters.',
            'branch_city.required' => 'The City field is required.',
            'branch_city.string' => 'The City must be a string.',
            'branch_city.max' => 'The City may not be greater than 150 characters.',
            'branch_state.required' => 'The State field is required.',
            'branch_state.string' => 'The State must be a string.',
            'branch_state.max' => 'The State may not be greater than 150 characters.',
            'branch_pin.required' => 'The PIN field is required.',
            'branch_pin.integer' => 'The PIN must be an integer.',
            'branch_pin.digits' => 'The PIN must be exactly 6 digits.',
            'branch_phone.required' => 'The Phone field is required.',
            'branch_phone.numeric' => 'The Phone must be a number.',
            'branch_phone.digits_between' => 'The Phone must be between 10 and 15 digits.',
            'branch_email.required' => 'The Email field is required.',
            'branch_email.email' => 'The Email must be a valid email address.',
            'branch_email.max' => 'The Email may not be greater than 255 characters.',
        ];
    }
}
