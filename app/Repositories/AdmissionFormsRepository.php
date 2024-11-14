<?php

namespace App\Repositories;
use App\Models\Schools;
use App\Models\Branches;
use App\Models\Branchmeta;
use Illuminate\Http\Request;
use App\Interfaces\AdmissionFormsInterface;
use App\Models\SchoolBrancheSettings;
use App\Models\AdmissionForms;
use App\Models\AdmissionFormsDetails;
use DB;
use App\Jobs\InsertStudentJob;

use Carbon\Carbon;

class AdmissionFormsRepository implements AdmissionFormsInterface
{
    public function __construct(AdmissionForms $admissionforms)
    {
        $this->admissionforms = $admissionforms;
    }

    public function createAadmissionforms($data)
    {
        $admission_id = $this->admissionforms->createAadmissionforms($data);
        AdmissionFormsDetails::createAadmissionformsdetails($admission_id, $data);

        if($data['payment_status'] == 'paid')
        {
            InsertStudentJob::dispatch($admission_id);
        }
        return "Create Successfully..";
    }

    public function getAadmissionforms(Request $request)
    {
        $branch_id = $request->input('branch_id');
        $limit = $request->input('_limit', 10);

        $query = $this->admissionforms->where('admission_forms.branch_id', $branch_id)
            ->join('admission_anouncements', 'admission_anouncements.id', '=', 'admission_forms.announcement_id')
            ->join('classes', 'classes.id', '=', 'admission_forms.class_id')
            ->join('academic_details', 'academic_details.id', '=', 'admission_forms.academic_year_id')
            ->join('admission_forms_details', 'admission_forms_details.admission_id', '=', 'admission_forms.id')
            ->select('admission_forms.*', 'admission_forms_details.father_name', 'classes.name as class_name', 'academic_details.start_date', 'academic_details.end_date');

        $total = $query->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where('admission_forms.first_name', 'like', "%{$search}%")
            ->orwhere('admission_forms.application_no', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('admission_forms.created_at', 'asc');
        }

        if ($limit <= 0) {
            $admissionformdata = $query->get();
        } else {
            $admissionformdata = $query->paginate($limit);
            $admissionformdata = $admissionformdata->items();
        }
        return ['data'=>$admissionformdata, 'total'=>$total];
    }

    public function getAadmissionformbyId($id)
    {
        $data = $this->admissionforms->where('admission_forms.id', $id)
        ->join('classes', 'classes.id', '=', 'admission_forms.class_id')
        ->join('admission_forms_details', 'admission_forms_details.admission_id', '=', 'admission_forms.id')
        ->select('admission_forms.*', 'admission_forms_details.*', 'classes.name as class_name')->first();
        return $data;
    }

    public function updateAadmissionformbyId($data, $id)
    {
        $admission_id = $this->admissionforms->updateAadmissionformbyId($data, $id);
        if($admission_id != 'False')
        {
            AdmissionFormsDetails::updateAadmissionformsdetails($id, $data);
            InsertStudentJob::dispatch($id);
            return "Update Successfull.";
        }
        return "False";
    }
}
