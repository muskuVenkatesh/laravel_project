<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\UpdateFieldsRequest;
use App\Interfaces\CertificateTypeInterface;
use App\Interfaces\CertificateTypeFieldInterface;
use App\Http\Requests\StoreCertificateTypeRequest;
use App\Http\Requests\StoreCertificateTypeFieldRequest;

class CertificateController extends Controller
{
    protected $certificateTypeRepo;
    protected $certificateTypeFieldRepo;

    public function __construct(
        CertificateTypeInterface $certificateTypeRepo,
        CertificateTypeFieldInterface $certificateTypeFieldRepo
    ) {
        $this->certificateTypeRepo = $certificateTypeRepo;
        $this->certificateTypeFieldRepo = $certificateTypeFieldRepo;
    }

    public function storeCertificateType(StoreCertificateTypeRequest $request)
    {
        $certificateType[] = $this->certificateTypeRepo->createCertificate($request->all());
        return response()->json(['certificatetype' =>$certificateType ,'message' => 'Certificate types created successfully'], 201);
    }

    public function getCertificateType($id)
    {
        $certificateType = $this->certificateTypeRepo->findById($id);
        if (!$certificateType) {
            throw new DataNotFoundException('Certificate not found');
        }else{
            return response()->json(['certificate'=>$certificateType], 200);
        }
    }

    public function getAllCertificateTypes()
    {
        $certificateTypes = $this->certificateTypeRepo->getAll();
        if ($certificateTypes && count($certificateTypes) > 0) {
            return response()->json(['certificate_types' => $certificateTypes], 200);
        }
        throw new DataNotFoundException('Certificate Not Found');
    }

    public function updateCertificateType(StoreCertificateTypeRequest $request, $id)
    {
        $certificateType = $this->certificateTypeRepo->updateCertificateType($request, $id);
        return response()->json(['message' => 'Certificate type and fields updated successfully'], 200);
    }

    public function deleteCertificateType($id)
    {
        $certificateType = $this->certificateTypeRepo->deleteCertificateType($id);
        return response()->json(['message'=>'Certificate Deleted Successfully']);
    }

    // Certificatefields Controller
    public function createCertificateField(StoreCertificateTypeFieldRequest $request)
    {
        $certificatefields= $this->certificateTypeFieldRepo->createCertificateField($request);
        if($certificatefields){
        return response()->json(['certificatefields' =>$certificatefields ,'message' => 'Certificate fields created successfully'], 201);
        }
    }

    public function getCertificateField($certificateTypeId)
    {
        $certificateTypeFields = $this->certificateTypeFieldRepo->getCertificateField($certificateTypeId);
        if ($certificateTypeFields['certificatetype'] === null) {
            throw new DataNotFoundException('Certificate not found');
        }
        if (count($certificateTypeFields['data']) === 0) {
            throw new DataNotFoundException('Certificate field not found');
        }
        return response()->json([
           'certificate_fields' => $certificateTypeFields['data']
        ], 200);
    }

    public function getCertificateFieldById($id){
        $certificateTypefield = $this->certificateTypeFieldRepo->getCertificateFieldById($id);
        if ($certificateTypefield) {
            return response()->json(['certificate_field' => $certificateTypefield], 200);
        }
        return throw new DataNotFoundException('Certificate field not found for the specified certificate type');
    }

    public function getAllCertificateFields()
    {
        $certificatefields= $this->certificateTypeFieldRepo->getCertificateFields();
        if ($certificatefields && count($certificatefields) > 0) {
            return response()->json(['certificate_fields' => $certificatefields], 200);
        }
        throw new DataNotFoundException('No Data Found For The Certificate Fields');
    }

    public function updateFieldsByCertificateId(UpdateFieldsRequest $request, $id)
    {
        $validatedData = $request->validated();
        $certificateField = $this->certificateTypeFieldRepo->updateFieldsByCertificateId($validatedData, $id);
        return response()->json(['certificateField' => $certificateField, 'message' => 'Certificate type and fields updated successfully'], 200);
    }

    public function deleteCertificateTypeField($id)
    {
        $certificateFielddelete = $this->certificateTypeFieldRepo->deleteCertificateFields($id);
        return response()->json(['message'=>'Certificate Field Deleted Successfully'], 200);
    }
}
