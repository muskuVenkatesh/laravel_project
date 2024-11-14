<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\CertificateDataRequest;
use App\Interfaces\CertificateDataInterface;
use App\Http\Requests\getcetificatefieldsRequest;

class CertificateDataController extends Controller
{
    protected $repository;

    public function __construct(CertificateDataInterface $certificatedatarepo)
    {
        $this->certificatedatarepo = $certificatedatarepo;
    }
    
    public function getCertificateFields(getcetificatefieldsRequest $request){
        $certificateType[] = $this->certificatedatarepo->getCertificateFields($request);
        return response()->json(['message' => 'Certificate successfully generated.'], 200);
    }

    public function generateCertificate(Request $request)
    {
        $this->certificatedatarepo->generateCertificate($request);
        return response()->json(['message' => 'Certificate successfully generated.'], 200);
    }

    public function generateCertificateList(Request $request)
    {
        $certificateList = $this->certificatedatarepo->generateCertificateList($request);
        if($certificateList->isNotEmpty())
        {
            return response()->json(['data'=>$certificateList], 200);
        }
        return response()->json(['data'=>"Data Not Found"], 404);
    }

    public function deleteCertificatePdf($id)
    {
        $certificateList = $this->certificatedatarepo->deleteCertificatePdf($id);
        if(!empty($certificateList))
        {
            return response()->json(['data'=> "Delteted Successfully..."], 200);
        }
        return response()->json(['data'=> "Data Not Found"], 404);
    }
}
