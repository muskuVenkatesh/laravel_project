<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransportPilotRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'branch_id'     => 'required|exists:branches,id',
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string',
            'alt_phone'     => 'required|string',
            'license_type'  => 'required|string|max:15',
            'license_no'    => 'required|string|unique:transport_pilot_details,license_no|max:255',
            'license_expire'=> 'required',
            'life_insurance' => 'required|in:1,0',
            'vehicle_id' => 'required|integer',
            'route_id' => 'required|integer',
        ];
    }
}
