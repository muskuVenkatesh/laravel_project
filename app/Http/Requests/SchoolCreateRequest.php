<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolCreateRequest extends FormRequest
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
            'academic_id' => 'required|exists:academic_details,id',
            'name' => 'required|string|max:150',
            'school_code'=> 'required|string|max:150|unique:schools,school_code',
            'affialiation_no' =>'nullable|string',
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
            'print_file' => 'required|mimes:jpeg,png,jpg,gif',
            'report_card' => 'required|mimes:jpeg,png,jpg,gif',
            'logo_file' => 'required|mimes:jpeg,png,jpg,gif',
            'text_logo' => 'required|mimes:jpeg,png,jpg,gif',
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
            'branch_name.required' => 'The Branch Name field is required.',
            'branch_name.string' => 'The Branch Name must be a string.',
            'branch_name.max' => 'The Branch Name may not be greater than 150 characters.',
            'branch_code.required' => 'The Branch code field is required.',
            'branch_code.string' => 'The Branch code must be a string.',
            'branch_code.max' => 'The Branch code may not be greater than 150 characters.',
            'branch_address.required' => 'The address field is required.',
            'branch_address.string' => 'The address must be a string.',
            'branch_address.max' => 'The address may not be greater than 150 characters.',
            'branch_city.required' => 'The city field is required.',
            'branch_city.string' => 'The city must be a string.',
            'branch_city.max' => 'The city may not be greater than 150 characters.',
            'branch_dist.required' => 'The district field is required.',
            'branch_dist.string' => 'The district must be a string.',
            'branch_dist.max' => 'The district may not be greater than 150 characters.',
            'branch_state.required' => 'The state field is required.',
            'branch_state.string' => 'The state must be a string.',
            'branch_state.max' => 'The state may not be greater than 150 characters.',
            'branch_pin.required' => 'The pin field is required.',
            'branch_pin.string' => 'The pin must be a integer.',

            //School Branch Setting
            'stud_grade.required' => 'The Branch Name field is required.',
            'stud_grade.string' => 'The Branch Name must be a string.',
            'stud_username_prefix.required' => 'The Student Username field is required.',
            'stud_username_prefix.string' => 'The Student Username must be a string.',
            'stud_username_prefix.max' => 'The Student Username may not be greater than 150 characters.',
            'stud_default_password.required' => 'The Password field is required.',
            'stud_default_password.string' => 'The Password must be a string.',
            'stud_default_password.max' => 'The Password may not be greater than 150 characters.',
            'grd_username_prefix.required' => 'The Gurdian Username field is required.',
            'grd_username_prefix.string' => 'The Gurdian Username must be a string.',
            'grd_username_prefix.max' => 'The Gurdian Username may not be greater than 150 characters.',
            'grd_default_password.required' => 'The Gurdian Password field is required.',
            'grd_default_password.string' => 'The Gurdian Password must be a string.',
            'grd_default_password.max' => 'The Gurdian Password may not be greater than 150 characters.',
            'currency.required' => 'The state currency is required.',
            'reg_start_from.required' => 'The registration Start field is required.',
            'reg_start_from.string' => 'The registration Start must be a integer.',
            'reg_prefix_digit.required' => 'The registration prefiex field is required.',
            'reg_prefix_digit.string' => 'The registration prefiex must be a integer.',
            'fees_due_days' =>'The fees Due Days field is required.',
            'fees_due_days' =>'The fees Due Days must be a integer.',
            'cal_fees_fine' =>'The calculate fees fine field is required.',
            'cal_fees_fine' =>'The calculate fees fine must be a integer.',

            'print_file' => 'The field is required.',
            'report_card' => 'The field is required.',
            'logo_file' => 'The field is required.',
            'text_logo' => 'The field is required.',
        ];
    }
}
