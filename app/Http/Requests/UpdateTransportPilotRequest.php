<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransportPilotRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        $pilotId = $this->route('pilot'); 

        return [
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string',
            'alt_phone'     => 'required|string',
            'license_type'  => 'required|string|max:15',
            'license_no'    => 'required|string',
            'license_expire'=> 'required',
            'life_insurance' => 'required|in:1,0',
            'vehicle_id'    => 'required|integer', 
            'route_id'      => 'required|integer',
        ];
    }
}
