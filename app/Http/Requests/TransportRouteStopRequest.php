<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransportRouteStopRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'route_id' => 'required|exists:transport_routes,id',
            'stop_data' => 'required|array',
        ];
    }
}
