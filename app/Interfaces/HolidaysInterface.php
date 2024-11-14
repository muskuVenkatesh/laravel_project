<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface HolidaysInterface 
{
    public function CreateHolidays($data);
    public function GetAllHolidays(Request $request);
    public function GetHolidaysById($id); 
    public function UpdateHolidays($id, $data);   
    public function softDeleteHolidays($id);
}    