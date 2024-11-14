<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransportDetailsRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'route_id' => 'required|exists:transport_routes,id',
            'student_id' => 'required|exists:students,id',
            'vehicle_id' => 'required|exists:transport_vehicles_details,id',
            'pilot_id' => 'required|exists:transport_pilot_details,id',
            'stop_name' => 'required|string|max:255',
         
        ];
    }
}
