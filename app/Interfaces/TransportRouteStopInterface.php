<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface TransportRouteStopInterface
{
    public function getAllStopsByRouteId($route_id, Request $request);
    public function getStopById($id);
    public function createStop($data);
    public function updateStop($id, $data);
    public function deleteStop($id);
}
