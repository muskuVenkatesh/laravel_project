<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface AdmissionFormsInterface
{
    public function createAadmissionforms($data);
    public function getAadmissionforms(Request $request);
    public function getAadmissionformbyId($id);
    public function updateAadmissionformbyId($validatedData, $id);
}
