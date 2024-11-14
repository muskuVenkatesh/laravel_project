<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PilotInterface 
{
    public function createpilot($data);
    public function getAllPilot(Request $request);
    public function getAllPilotbyid($id);
    public function updatePilot($id,$data);
    public function deletepilot($id);
}