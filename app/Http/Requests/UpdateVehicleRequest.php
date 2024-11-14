<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
   
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'vehicle_type' => 'required|in:bus,mini-bus,car',
            'vehicle_no' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'insurance_expire' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'branch_id.required' => 'The branch ID is required.',
            'vehicle_type.required' => 'The vehicle type is required.',
            'vehicle_no.required' => 'The vehicle number is required.',
            'capacity.required' => 'The capacity is required.',
            'insurance_expire.required' => 'The insurance expiry date is required.',
        ];
    }
}
