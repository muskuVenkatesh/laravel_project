<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransportDetailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'transport' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'branch_id.required' => 'The branch ID is required.',
            'branch_id.exists' => 'The selected branch does not exist.',
            'route_id.required' => 'The route ID is required.',
            'route_id.exists' => 'The selected route does not exist.',
            'vehicle_id.required' => 'The vehicle ID is required.',
            'vehicle_id.exists' => 'The selected vehicle does not exist.',
            'pilot_id.required' => 'The pilot ID is required.',
            'pilot_id.exists' => 'The selected pilot does not exist.',
        ];
    }
}
