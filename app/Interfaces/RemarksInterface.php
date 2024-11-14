<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface RemarksInterface 
{
    public function CreateRemarks($data);
    public function GetRemarkById($id);
    public function UpdateRemarks($id, $data);
    public function SoftDeleteRemarks($id);
    public function GetAllRemarks(Request $request);
}    