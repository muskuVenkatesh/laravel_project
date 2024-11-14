<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffCreateRequest extends FormRequest
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
            'branch_id' => 'required|exists:branches,id',
            'date_of_birth' => 'required',
            'role_id' => 'required|exists:roles,id',
            'gender' => 'required',
            'blood_group' => 'nullable|string|max:255|present',
            'religion' => 'nullable|string|max:255|present',
            'cast' => 'nullable|string|max:255|present',
            'image' => 'nullable|image|max:2048|present',
            'mother_tounge' => 'nullable|string|max:255|present',
            'aadhaar_card_no' => 'required|string|present',
            'pan_card_no' => 'required|string|present',
            'address' => 'nullable|string|max:255|present',
            'city' => 'nullable|string|max:255|present',
            'state' => 'nullable|string|max:255|present',
            'country' => 'nullable|string|max:255',
            'pin' => 'nullable|digits:6|present',
            'tmp_address' => 'nullable|string|max:255|present',
            'temp_city' => 'nullable|string|max:255|present',
            'temp_state' => 'nullable|string|max:255|present',
            'temp_pin' => 'nullable|digits:6|present',
            'temp_country' => 'nullable|string|max:255',
            'employee_no' => 'nullable|string|max:255|present',
            'first_name' => 'required|string|max:255|present',
            'middle_name' => 'nullable|string|max:255|present',
            'last_name' => 'required|string|max:255|present',
            'email' => 'required|email|unique:users,email|present',
            'epf_no' => 'nullable|string|max:255|present',
            'uan_no' => 'nullable|string|max:255|present',
            'esi_no' => 'nullable|string|max:255|present',
            'marital_status' => 'nullable|in:single,married,divorced,widowed|present',
            'anniversary_date' => 'nullable|present',
            'spouse_name' => 'nullable|string|max:255|present',
            'kid_studying' => 'nullable|in:yes,no|present',
            'assigned_activity' => 'nullable|string|max:255|present',
            'joining_date' => 'required|present',
            'specialized' => 'nullable|present',
            'department' => 'nullable|present',
            'work_location' => 'nullable|string|max:255|present',
            'qualification' => 'nullable|present',
            'extra_qualification' => 'nullable|present',
            'previous_school' => 'nullable|string|max:255|present',
            'reason_change' => 'nullable|string|max:255|present',
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

            'role_id.required' => 'The Role is required.',
            'role_id.exists' => 'The selected Role is invalid.',
            'branch_id.required' => 'The branch is required.',
            'branch_id.exists' => 'The selected branch is invalid.',
            'date_of_birth.required' => 'The date of birth is required.',
            'date_of_birth.date' => 'The date of birth is not a valid date.',
            'gender.required' => 'The gender field is required.',
            'gender.in' => 'The selected gender is invalid.',
            'blood_group.string' => 'The blood group must be a string.',
            'religion.string' => 'The religion must be a string.',
            'religion.max' => 'The religion may not be greater than 255 characters.',
            'cast.string' => 'The cast must be a string.',
            'cast.max' => 'The cast may not be greater than 255 characters.',
            'image.image' => 'The image must be an image.',
            'image.max' => 'The image may not be greater than 2048 kilobytes.',
            'mother_tounge.string' => 'The mother tongue must be a string.',
            'mother_tounge.max' => 'The mother tongue may not be greater than 255 characters.',
            'aadhaar_card_no.string' => 'The Aadhar card number must be a string.',
            'aadhaar_card_no.size' => 'The Aadhar card number must be 12 characters.',
            'aadhaar_card_no.unique' => 'The Aadhar card number has already been taken.',
            'pan_card_no.string' => 'The PAN card number must be a string.',
            'pan_card_no.size' => 'The PAN card number must be 10 characters.',
            'pan_card_no.unique' => 'The PAN card number has already been taken.',
            'address.required' => 'The address is required.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 255 characters.',
            'city.required' => 'The city is required.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city may not be greater than 255 characters.',
            'state.required' => 'The state is required.',
            'state.string' => 'The state must be a string.',
            'state.max' => 'The state may not be greater than 255 characters.',
            'country.required' => 'The country is required.',
            'country.string' => 'The country must be a string.',
            'country.max' => 'The country may not be greater than 255 characters.',
            'pin.required' => 'The pin code is required.',
            'pin.digits' => 'The pin code must be 6 digits.',
            'tmp_address.string' => 'The temporary address must be a string.',
            'tmp_address.max' => 'The temporary address may not be greater than 255 characters.',
            'temp_city.string' => 'The temporary city must be a string.',
            'temp_city.max' => 'The temporary city may not be greater than 255 characters.',
            'temp_state.string' => 'The temporary state must be a string.',
            'temp_state.max' => 'The temporary state may not be greater than 255 characters.',
            'temp_pin.digits' => 'The temporary pin code must be 6 digits.',
            'temp_country.string' => 'The temporary country must be a string.',
            'temp_country.max' => 'The temporary country may not be greater than 255 characters.',
            'employee_no.required' => 'The employee number is required.',
            'employee_no.string' => 'The employee number must be a string.',
            'employee_no.max' => 'The employee number may not be greater than 255 characters.',
            'first_name.required' => 'The first name is required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The first name may not be greater than 255 characters.',
            'middle_name.string' => 'The middle name must be a string.',
            'middle_name.max' => 'The middle name may not be greater than 255 characters.',
            'last_name.required' => 'The last name is required.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name may not be greater than 255 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'epf_no.string' => 'The EPF number must be a string.',
            'epf_no.max' => 'The EPF number may not be greater than 255 characters.',
            'uan_no.string' => 'The UAN number must be a string.',
            'uan_no.max' => 'The UAN number may not be greater than 255 characters.',
            'esi_no.string' => 'The ESI number must be a string.',
            'esi_no.max' => 'The ESI number may not be greater than 255 characters.',
            'marital_status.in' => 'The selected marital status is invalid.',
            'anniversary_date.date' => 'The anniversary date is not a valid date.',
            'spouse_name.string' => 'The spouse name must be a string.',
            'spouse_name.max' => 'The spouse name may not be greater than 255 characters.',
            'kid_studying.required' => 'The kid studying field is required.',
            'kid_studying.in' => 'The selected kid studying value is invalid.',
            'assigned_activity.string' => 'The assigned activity must be a string.',
            'assigned_activity.max' => 'The assigned activity may not be greater than 255 characters.',
            'joining_date.required' => 'The joining date is required.',
            'joining_date.date' => 'The joining date is not a valid date.',
            'specialized.required' => 'The specialization is required.',
            'specialized.exists' => 'The selected specialization is invalid.',
            'department.required' => 'The department is required.',
            'department.string' => 'The department must be a string.',
            'department.max' => 'The department may not be greater than 255 characters.',
            'work_location.string' => 'The work location must be a string.',
            'work_location.max' => 'The work location may not be greater than 255 characters.',
            'qualification.required' => 'The qualification is required.',
            'extra_qualification.exists' => 'The selected extra qualification is invalid.',
            'previous_school.string' => 'The previous school must be a string.',
            'previous_school.max' => 'The previous school may not be greater than 255 characters.',
            'reason_change.string' => 'The reason for change must be a string.',
            'reason_change.max' => 'The reason for change may not be greater than 255 characters.',
        ];
    }
}
