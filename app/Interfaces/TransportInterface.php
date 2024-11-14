<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface TransportInterface 
{
    public function CreateTransport($data);
    public function GetAllTransportDetails(Request $request);
    public function GetTransportDetailsById($id);
    public function UpdateTransportDetails($id, $data);
    public function DeleteTransportDetails($id);
    public function getTransportDetailsByStudentId($student_id);
}    