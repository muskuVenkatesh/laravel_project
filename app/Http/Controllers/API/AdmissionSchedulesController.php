<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\AdmissionSchedules;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\AdmissionSchedulesInterface;
use App\Http\Requests\AdmissionSchedulesRequest;
use App\Http\Requests\AdmissionSchedulesUpdateRequest;

class AdmissionSchedulesController extends Controller
{
    protected $admissionschedulesinterface;

    public function __construct(AdmissionSchedulesInterface $admissionschedulesinterface)
    {
        $this->admissionschedulesinterface=$admissionschedulesinterface;
    }

    public function createAdmissionSchedules(AdmissionSchedulesRequest $request)
    {
        $validatedData = $request->validated();
        $AdmissionSchedules = $this->admissionschedulesinterface->createAdmissionSchedules($validatedData);
        return response()->json(['message' => 'Admission Schedules created successfully!'], 200);
    }

    public function getschedules(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $allSchedules = $this->admissionschedulesinterface->getSchedules($request, $perPage);
        if (empty($allSchedules['data'])|| $allSchedules['total'] == 0){
            throw new DataNotFoundException('AdmissionSchedules Data not found');
        }
        else  {
            return response()->json([
                'data' => $allSchedules['data'],
                'total' => $allSchedules['total']
            ], 200);
        }
    }

    public function getSchedulesById(Request $request,$id)
    {
        $allSchedules = $this->admissionschedulesinterface->getSchedulesById($id);
        if(!$allSchedules){
            throw new DataNotFoundException('Data not found');
        }
        return response()->json([
            'data' => $allSchedules,
        ], 200);
    }

    public function updateschedules(AdmissionSchedulesUpdateRequest $request)
    {
        $validatedData = $request->validated();
        $result = $this->admissionschedulesinterface->updateschedules($validatedData);
        return response()->json(['data' => $result], 200);
    }

    public function deleteschedules($id)
    {
        $result = $this->admissionschedulesinterface->deleteSchedules($id);
        return response()->json(['message' => $result]);
    }
}
