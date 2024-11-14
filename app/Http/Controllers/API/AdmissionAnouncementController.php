<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\AdmissionEnquiry;
use App\Http\Controllers\Controller;
use App\Models\AdmissionAnouncement;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\AdmissionAnouncementInterface;
use App\Http\Requests\AdmissionAnnouncementRequest;
use App\Http\Requests\AdmissionAnnouncementUpdateRequest;

class AdmissionAnouncementController extends Controller
{
    protected $admissionAnnouncementInterface;

    public function __construct(AdmissionAnouncementInterface $admissionAnnouncementInterface)
    {
        $this->admissionAnnouncementInterface = $admissionAnnouncementInterface;
    }

    public function createAdmissionAnnouncement(AdmissionAnnouncementRequest $request)
    {
        $validatedData = $request->validated();

        $admissionAnnouncement = $this->admissionAnnouncementInterface->createAdmissionAnnouncement($validatedData);

        return response()->json(['message' => 'Admission Announcement created successfully!'], 200);
    }

    public function getAnnouncements(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $allAnnouncement = $this->admissionAnnouncementInterface->getAnnouncements($request, $perPage);
        if (empty($allAnnouncement['data']) || $allAnnouncement['total'] == 0){
            throw new DataNotFoundException('Announcement not found');
        }
        else  {
            return response()->json([
                'data' => $allAnnouncement['data'],
                'total' => $allAnnouncement['total']
            ], 200);
        }
    }

    public function getAnouncementById(Request $request,$id)
    {
        $allAnnouncement = $this->admissionAnnouncementInterface->getAnouncementById($id);
        if (!$allAnnouncement) {
            throw new DataNotFoundException('Data not found.');
        }
        return response()->json(['announcement'=>$allAnnouncement],200);
    }

    public function updateAnouncement(AdmissionAnnouncementUpdateRequest $request, $id)
    {
        $validatedData = $request->validated();
        $result = $this->admissionAnnouncementInterface->updateAnouncement($validatedData, $id);
        return response()->json(['message' => $result], 200);
    }

    public function deleteAnnouncement($id)
    {
        $announcement = AdmissionAnouncement::find($id);
        $enquiryCount = AdmissionEnquiry::where('announcement_id', $id)->count();
        if ($enquiryCount > 0) {
            return response()->json([
                'message' => 'Cannot delete announcement. There are related admission enquiries.'
            ], 405);
        }
        $announcement->status = 0;
        $announcement->save();
        return response()->json(['message' => 'Admission Announcement deleted successfully.']);
    }
}
