<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface TransportRouteInterface
{
    public function getAllTransportRoutes(Request $request);
    public function getTransportRouteById($id);
    public function createTransportRoute($data);
    public function updateTransportRoute($id, $data);
    public function deleteTransportRoute($id);
    public function getRoutes(Request $request);
}
