<?php
namespace App\Repositories;
use Illuminate\Http\Request;
use App\Models\CertificateType;
use App\Interfaces\CertificateTypeInterface;

class CertificateTypeRepository implements CertificateTypeInterface
{
    public function __construct(CertificateType $certificateType)
    {
        $this->certificateType =$certificateType;
    }

    public function createCertificate($data)
    {
        $certificateType = $this->certificateType->create([
            'certificate_type' => $data['certificate_type'],
        ]);
        return $certificateType;
    }

    public function findById($id)
    {
        return $this->certificateType->where('status',1)->find($id);
    }

    public function getAll(){
        return $this->certificateType->where('status',1)->get();
    }

    public function updateCertificateType(Request $request,$id){
        $certificateType = $this->certificateType->find($id);

        if (!$certificateType) {
            return response()->json(['message' => 'Certificate type not found'], 404);
        }
        $updateCertificate = $certificateType->update([
            'certificate_type' => $request->certificate_type,
        ]);

        return $updateCertificate;
    }

    public function deleteCertificateType($id){
        $certificateType = $this->certificateType->find($id);
        if($certificateType){
            $certificateType->status = 0;
            $certificateType->save();
        }
        if (!$certificateType)
        {
           return response()->json(['message' => 'Certificate type not found'], 404);
        }
        return $certificateType;
    }
}
