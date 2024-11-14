<?php

namespace App\Repositories;
use App\Models\Schools;
use App\Models\Branches;
use App\Models\Branchmeta;
use Illuminate\Http\Request;
use App\Interfaces\AdmissionEnquiryInterface;
use App\Models\SchoolBrancheSettings;
use App\Models\AdmissionEnquiry;
use App\Models\AdmissionSchedules;
use DB;
use Carbon\Carbon;

class AdmissionEnquiryRepository implements AdmissionEnquiryInterface
{
    public function __construct(AdmissionEnquiry $admissionenquiry)
    {
        $this->admissionenquiry = $admissionenquiry;
    }

    public function createAdmissionEnquery($validatedData)
    {
        $data= $this->admissionenquiry->createAdmissionEnquery($validatedData);
        if($data->id !== '')
        {
            AdmissionSchedules::create([
                'enquiry_id' => $data->id,
                'schedule_status' => 0
            ]);
            return 'Admission Enquiry created successfully!';
        }
        return 'Admission Enquiry Not created successfully!';
    }

    public function getAdmissionEnquery(Request $request, $limit)
    {
        $announcement_id = $request->input('announcement_id');
        $allEnquery = $this->admissionenquiry->where('admission_enquiries.announcement_id', $announcement_id)
        ->where('admission_enquiries.status', 1)
        ->join('classes', 'classes.id', '=', 'admission_enquiries.class_applied')
        ->select('admission_enquiries.*', 'classes.name as class_name');

        $total = $allEnquery->count();
        if ($request->has('q')) {
            $search = $request->input('q');
            $allEnquery->where('name', 'like', "%{$search}%")
            ->orWhere('father_name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allEnquery->orderBy($sortBy, $sortOrder);
        } else {

            $allEnquery->orderBy('created_at', 'asc');
        }
        if ($limit <= 0) {
            $allEnqueryData = $allEnquery->get();
        } else {
            $allEnqueryData = $allEnquery->paginate($limit);
            $allEnqueryData = $allEnqueryData->items();
        }
        return ['data' => $allEnqueryData, 'total' => $total];
    }

    public function getAdmissionEnqueryByid($id)
    {
        $data= $this->admissionenquiry->where('status', 1)->where('id', $id)->first();
        return $data;
    }

    public function updateAdmissionEnquery($validatedData, $id)
    {
        $data= $this->admissionenquiry->updateAdmissionEnquery($validatedData, $id);
        return 'Admission Enquiry updated successfully!';
    }
    public function deleteAdmissionEnquery($id)
    {
        $enquiry = AdmissionEnquiry::find($id);
        $enquiry->status = 0;
        $enquiry->save();
        $enquiry->delete();
        return ['message' => 'Admission Enquiry deleted successfully.'];
    }

    public function updateAmissionStatus($id)
    {
        $enquiry = AdmissionEnquiry::find($id);
        $enquiry->admission_status = 1;
        $enquiry->save();
        $enquiry->delete();
        return ['message' => 'Admission Schedule updated successfully.'];
    }
}
