<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface TransportVehicleInterface 
{
    public function createTransportVehicle($data);
    public function getAllTransportVehicle(Request $request);
    public function GetTransportVehicle($id);
    public function UpdateTransportVehicle($id,$data);
    public function DeleteTransportVehicle($id);
}