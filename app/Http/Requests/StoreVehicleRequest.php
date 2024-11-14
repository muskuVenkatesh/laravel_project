<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'branch_id' => 'required|integer|exists:branches,id',
            'vehicle_type' => 'required|in:bus,mini-bus,car',
            'vehicle_no' => 'required|string|max:255|unique:transport_vehicles_details,vehicle_no',
            'capacity' => 'required|integer',
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
