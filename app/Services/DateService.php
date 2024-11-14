<?php

namespace App\Services;

use Carbon\Carbon;

class DateService
{
    public function formatDate($date)
    {
        return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
    }

    public function IncressMonth($payTimelineDate, $data)
    {
        return Carbon::parse($payTimelineDate)->addMonths($data);
    }

    public function databaseDateFormate($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('d-M-Y');
    }

    public function dateMonthYearFormat($date)
    {
        return Carbon::parse($date)->format('M Y');
    }
}
