<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransportRouteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'start_point' => 'required|max:255|unique:transport_routes,start_point',
            'end_point' => 'required|max:255|different:start_point|unique:transport_routes,end_point',
            'start_latitude' => 'required',
            'start_logitude' => 'required',
            'end_latitude' => 'required',
            'end_logitude' => 'required',
            'distance' => 'required',
        ];
    }
}
