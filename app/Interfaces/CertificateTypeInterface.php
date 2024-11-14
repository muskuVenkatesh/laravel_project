<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface CertificateTypeInterface
{
    public function createCertificate($data);
    public function findById($id);
    public function getAll();
    public function updateCertificateType(Request $request,$id);
    public function deleteCertificateType($id);

}
