<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\AdmissionForms;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\AdmissionFormsInterface;
use App\Http\Requests\AdmissionCreateFormRequest;
use App\Http\Requests\AdmissionUpdateFormRequest;

class AdmissionFormsController extends Controller
{
    protected $admissionforminterface;
    public function __construct(AdmissionFormsInterface $admissionforminterface)
    {
        $this->admissionforminterface = $admissionforminterface;
    }

    public function createAadmissionforms(AdmissionCreateFormRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->admissionforminterface->createAadmissionforms($validatedData);
        return response()->json([
            'data' =>$data
        ]);
    }

    public function getAadmissionforms(Request $request)
    {
        $data = $this->admissionforminterface->getAadmissionforms($request);
        if (empty($data['data']) || $data['total'] == 0){
            throw new DataNotFoundException('Admissionforms Data not found');
        }
        return response()->json([
            'data' => $data['data'],
            'total' => $data['total']
        ]);
    }

    public function getAadmissionformbyId($id)
    {
        $data = $this->admissionforminterface->getAadmissionformbyId($id);
        if(!$data){
            throw new DataNotFoundException('Data not found.');
        }
        return response()->json([
            'data' => $data,
        ]);
    }

    public function updateAadmissionformbyId(AdmissionUpdateFormRequest $request, $id)
    {
        $validatedData = $request->validated();
        $data = $this->admissionforminterface->updateAadmissionformbyId($validatedData, $id);
        if($data == 'False')
        {
            return response()->json([
                'data' =>"Its Already Paid"
            ], 500);
        }
        return response()->json([
            'data' =>$data
        ], 200);
    }
}
