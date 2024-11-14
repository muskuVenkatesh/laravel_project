<?php

namespace App\Interfaces;


use Illuminate\Http\Request;


interface AdmissionEnquiryInterface
{
   public function createAdmissionEnquery($validatedData);
   public function getAdmissionEnqueryByid($id);
   public function updateAdmissionEnquery($validatedData, $id);
   public function deleteAdmissionEnquery($id);
   public function getAdmissionEnquery(Request $request, $perPage);
   public function updateAmissionStatus($id);
}
