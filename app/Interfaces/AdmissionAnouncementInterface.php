<?php
namespace App\Interfaces;
use Illuminate\Http\Request;

interface AdmissionAnouncementInterface
{

    public function createAdmissionAnnouncement($validatedData);
    public function getAnnouncements(Request $request, $perPage);
    public function getAnouncementById($id);
    public function updateAnouncement($validatedData, $id);
    public function deleteAnnouncement($id);

}
