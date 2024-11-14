<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\AdmissionEnquiry;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\AdmissionEnquiryInterface;
use App\Http\Requests\AdmissionEnquiryCreateRequest;
use App\Http\Requests\AdmissionEnquiryUpadateRequest;

class AdmissionEnquiryController extends Controller
{
    protected $admissionEnquiryInterface;

    public function __construct(AdmissionEnquiryInterface $admissionEnquiryInterface)
    {
        $this->admissionEnquiryInterface = $admissionEnquiryInterface;
    }

    public function createAdmissionEnquery(AdmissionEnquiryCreateRequest $request)
    {
        $validatedData = $request->validated();
        $admissionEnquiry = $this->admissionEnquiryInterface->createAdmissionEnquery($validatedData);
        return response()->json(['message' => $admissionEnquiry], 200);
    }

    public function getAdmissionEnquery(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $admissionEnquiry = $this->admissionEnquiryInterface->getAdmissionEnquery($request, $perPage);
        if (empty($admissionEnquiry['data']) || $admissionEnquiry['total'] == 0){
            throw new DataNotFoundException('AdmissionEnquiry Data not found');
        }
        else  {
            return response()->json([
                'data' => $admissionEnquiry['data'],
                'total' => $admissionEnquiry['total']
            ], 200);
        }
    }
    public function getAdmissionEnqueryByid($id)
    {
        $admissionEnquiry = $this->admissionEnquiryInterface->getAdmissionEnqueryByid($id);
        if (!$admissionEnquiry) {
            throw new DataNotFoundException('Data not found.');
        }
        return response()->json(['data'=>$admissionEnquiry],200);
    }

    public function updateAdmissionEnquery(AdmissionEnquiryUpadateRequest $request, $id)
    {
        $validatedData = $request->validated();
        $admissionEnquiry = $this->admissionEnquiryInterface->updateAdmissionEnquery($validatedData, $id);
        return response()->json(['message' => $admissionEnquiry], 200);
    }

    public function deleteAdmissionEnquery($id)
    {
        $result = $this->admissionEnquiryInterface->deleteAdmissionEnquery($id);
        return response()->json(['message' => $result['message']], 200);
    }

    public function updateAmissionStatus($id)
    {
        $result = $this->admissionEnquiryInterface->updateAmissionStatus($id);
        return response()->json(['message' => $result['message']], 200);
    }
}
