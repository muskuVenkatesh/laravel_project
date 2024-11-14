<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

abstract class Controller
{
    public function getToday()
    {
        return Carbon::now()->format('Y-m-d');
    }

    public function getFormattedDate($attendanceDate)
    {
        return Carbon::createFromFormat('d/m/Y', $attendanceDate)->format('Y-m-d');
    }
}
