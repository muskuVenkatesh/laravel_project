<?php
namespace App\Interfaces;

interface CertificateTypeFieldInterface
{
    public function createCertificateField(array $data);
    public function getCertificateField($certificateTypeId);
    public function getCertificateFields();
    public function updateFieldsByCertificateId($data, $certificateId);
    public function deleteCertificateFields($certificate_type_id);
    public function getCertificateFieldById($id);
}
