<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface FeesAcademicSetupInterface
{
    public function createAcademicSetup($data);
    public function getAcademicSetup(Request $request);
    public function getAcademicSetupById($id);
    public function updateAcademicSetup($validatedData, $id);
    public function getStudentAcademicFees(Request $request);
    public function getFeesDashboard(Request $request);
}
