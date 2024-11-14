<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\HolidaysRepository;
use App\Http\Requests\HolidayRequest; 
use App\Interfaces\HolidaysInterface;

class HolidaysController extends Controller
{
    protected $HolidaysInterface;

    public function __construct(HolidaysInterface $HolidaysInterface)
    {
        $this->HolidaysInterface = $HolidaysInterface;
    }

    public function CreateHolidays(HolidayRequest $request)
    {
        $data = $request->validated();
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('holiday_images', 'public'); 
            $data['image'] = $imagePath; 
        }
    
       
        $Holidays = $this->HolidaysInterface->CreateHolidays($data);
    
        return response()->json([
            'message' => 'Created successfully',
        ], 200);
    }

    public function GetAllHolidays(Request $request)
    {
        $Holidays = $this->HolidaysInterface->GetAllHolidays($request);
        return response()->json($Holidays, 200);
    }

    public function GetHolidaysById($id)
    {
        $holidays = $this->HolidaysInterface->GetHolidaysById($id);
        if (!$holidays) {
            return response()->json(['message' => 'Holidays not found'], 404);
        }
        return response()->json($holidays, 200);
    }

    public function UpdateHolidays(HolidayRequest $request, $id)
    {
        $data = $request->validated();

        $existingHoliday = $this->HolidaysInterface->GetHolidaysById($id);
        
        if (!$existingHoliday) {
            return response()->json(['message' => 'Holiday not found'], 404);
        }
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('holiday_images', 'public'); 
            $data['image'] = $imagePath;
        }
        try {
            $updatedHoliday = $this->HolidaysInterface->UpdateHolidays($id, $data);
            return response()->json([
                'message' => 'Holiday updated successfully',
                'holiday' => $updatedHoliday,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update holiday', 'error' => $e->getMessage()], 500);
        }
    }

    public function softDeleteHolidays($id)
    {
        $existingHoliday = $this->HolidaysInterface->GetHolidaysById($id);
        if (!$existingHoliday) {
            return response()->json(['message' => 'Holiday not found'], 404);
        }
        $deletedHoliday = $this->HolidaysInterface->softDeleteHolidays($id);
        return response()->json([
            'message' => 'Holiday deleted successfully',
            'holiday' => $deletedHoliday,
        ], 200);
    }

}
