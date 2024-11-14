<?php

namespace App\Repositories;

use App\Models\AdmissionAnouncement;
use App\Interfaces\AdmissionAnouncementInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdmissionAnnouncementRepositoty implements AdmissionAnouncementInterface
{
    public function __construct(AdmissionAnouncement $admissionannouncement)
    {
           $this->admissionannouncement = $admissionannouncement;
    }

    public function createAdmissionAnnouncement($validatedData)
    {
        $data = $this->admissionannouncement->createAdmissionAnnouncement($validatedData);
        return "Created Successfully...";
    }

    public function getAnnouncements(Request $request, $limit)
    {
        $branchId = $request->input('branch_id');
        $allAnnouncements = AdmissionAnouncement::where('admission_anouncements.branch_id', $branchId)
        ->where('admission_anouncements.status', 1)
        ->join('academic_details', 'academic_details.id', '=', 'admission_anouncements.academic_year_id')
        ->join('classes', 'classes.id', '=', 'admission_anouncements.class')
        ->select('admission_anouncements.*', 'classes.name as class_name', 'academic_details.academic_years');

        $total = $allAnnouncements->count();
        if ($request->has('q')) {
            $search = $request->input('q');
            $allAnnouncements->where('name', 'like', "%{$search}%");
        }
        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allAnnouncements->orderBy($sortBy, $sortOrder);
        } else {

            $allAnnouncements->orderBy('created_at', 'asc');
        }
        if ($limit <= 0) {
            $announcementData = $allAnnouncements->get();
        } else {
            $announcementData = $allAnnouncements->paginate($limit);
            $announcementData = $announcementData->items();
        }
        return ['data' => $announcementData, 'total' => $total];
    }

    public function getAnouncementById($id)
    {
        $announcement = $this->admissionannouncement->
        join('classes', 'classes.id', '=', 'admission_anouncements.class')
        ->where('admission_anouncements.id', $id)
        ->select('classes.name as class_name', 'admission_anouncements.*')->first();

        if ($announcement) {
            return $announcement;
        } else {
            return ['message' => 'Data not found.'];
        }
    }
    public function updateAnouncement($data, $id)
    {
        $announcement = $this->admissionannouncement->updateAnouncement($id, $data);
        return $announcement;
    }

    public function deleteAnnouncement($id)
    {
        $announcement = $this->admissionannouncement->find($id);
        $announcement->status = 0;
        $announcement->save();
        $announcement->delete();
        return ['message' => 'Admission Announcement deleted successfully.'];
    }
}


