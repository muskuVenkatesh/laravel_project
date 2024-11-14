<?php

namespace App\Repositories;

use App\Models\AdmissionSchedules;
use App\Models\AdmissionAnouncement;
use App\Models\AdmissionEnquiry;
use App\Interfaces\AdmissionSchedulesInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class AdmissionSchedulesRepositoty implements AdmissionSchedulesInterface
{
    public function __construct(AdmissionSchedules $admissionschedules)
    {
        $this->admissionschedules = $admissionschedules;
    }

    public function createAdmissionSchedules($validatedData)
    {
        $data = $this->admissionschedules->createAdmissionSchedules($validatedData);
        return $data;
    }

    public function getSchedules(Request $request, $limit)
    {
        $announcement_id = $request->input('announcement_id');
        $shedule_status = $request->input('schedule_status');
        $allSchedules = AdmissionEnquiry::where('admission_enquiries.announcement_id', $announcement_id)
        ->join('admission_anouncements', 'admission_anouncements.id', '=', 'admission_enquiries.announcement_id')
        ->join('admission_schedules', 'admission_enquiries.id', '=', 'admission_schedules.enquiry_id')
        ->join('classes', 'classes.id', '=', 'admission_enquiries.class_applied');
        if($shedule_status != '')
        {
            $allSchedules->where('admission_schedules.schedule_status', $shedule_status);
        }
        $allSchedules->select( 'admission_schedules.id as schedule_id', 'admission_schedules.*', 'admission_anouncements.name as anouncements_name','classes.name as class_name' ,'admission_enquiries.*');

        $total = $allSchedules->count();
        if ($request->has('q')) {
            $search = $request->input('q');
            $allSchedules->where('admission_schedules.venue', 'like', "%{$search}%");
        }
        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allSchedules->orderBy($sortBy, $sortOrder);
        } else {
            $allSchedules->orderBy('admission_schedules.created_at', 'asc');
        }
        if ($limit <= 0) {
            $Schedulesdata = $allSchedules->get();
        } else {
            $Schedulesdata = $Schedulesdata->paginate($limit);
            $Schedulesdata = $Schedulesdata->items();
        }
        return ['data' => $Schedulesdata, 'total' => $total];
    }

    public function getSchedulesById($id)
    {
        $Schedules = $this->admissionschedules->find($id);
        if ($Schedules) {
            return $Schedules;
        } else {
            return ['message' => 'Data not found.'];
        }
    }

    public function updateSchedules($data)
    {
        $Schedules = $this->admissionschedules->updateSchedules($data);
        return $Schedules;
    }

    public function deleteSchedules($id)
    {
        $Schedules = $this->admissionschedules->find($id);
        $Schedules->schedule_status = 0;
        $Schedules->save();
        $Schedules->delete();
        return ['message' => 'Admission Schedule status updated to inactive successfully.'];
    }
}
