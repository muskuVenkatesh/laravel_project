<?php
namespace App\Interfaces;
use Illuminate\Http\Request;

interface AdmissionSchedulesInterface
{
    public function createAdmissionSchedules($validatedData);
    public function getSchedules(Request $request, $perPage);
    public function getSchedulesById($id);
    public function updateSchedules($validatedData);
    public function deleteSchedules($id);
}
