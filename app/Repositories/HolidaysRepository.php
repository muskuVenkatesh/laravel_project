<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Holidays;
use App\Interfaces\HolidaysInterface;

class HolidaysRepository implements HolidaysInterface
{
    protected $holidays;
    public function __construct(holidays $holidays)
    {
        $this->holidays = $holidays;
    }

    public function CreateHolidays($data)
    {
        return $this->holidays->create($data);
    }

    public function GetAllHolidays(Request $request)
    {
        $limit = $request->input('_limit');
        $allholidays = Holidays::where('status', 1);
        $total = $allholidays->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $allholidays->where('name', 'like', "%{$search}%");
        }
        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allholidays->orderBy($sortBy, $sortOrder);
        } else {
            $allholidays->orderBy('created_at', 'asc');
        }
        if ($limit <= 0) {
            $allHolidaysData = $allholidays->get();
        } else {
            $allHolidaysData = $allholidays->paginate($limit);
            $allHolidaysData = $allHolidaysData->items();
        }
        return ['data'=>$allHolidaysData,'total'=>$total];
    }

    public function GetHolidaysById($id)
    {
        return $this->holidays->find($id); 
    }

    public function UpdateHolidays($id, $data)
    {
        $holiday = $this->holidays->find($id);
        if (!$holiday) {
            return null; 
        }
        $holiday->fill($data);
        $holiday->save();

        return $holiday;
    }

    public function softDeleteHolidays($id)
    {
        $holiday = $this->holidays->find($id);

        if (!$holiday) {
            return null; 
        }
        $holiday->status = 0; 
        $holiday->save();

        return $holiday;
    }
}