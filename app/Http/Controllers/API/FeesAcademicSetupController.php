<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\AdmissionForms;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\FeesAcademicSetupInterface;
use App\Http\Requests\FeesAcademicSetupCreateFormRequest;
use App\Http\Requests\FeesAcademicSetupUpdateFormRequest;

class FeesAcademicSetupController extends Controller
{
    protected $feesacademicsetupinterface;
    public function __construct(FeesAcademicSetupInterface $feesacademicsetupinterface)
    {
        $this->feesacademicsetupinterface = $feesacademicsetupinterface;
    }

    public function createAcademicSetup(FeesAcademicSetupCreateFormRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->feesacademicsetupinterface->createAcademicSetup($validatedData);
        return response()->json
        ([
            'data' => $data
        ], 200);
    }

    public function getAcademicSetup(Request $request)
    {
        $data = $this->feesacademicsetupinterface->getAcademicSetup($request);
        if(empty($data['data']) || empty($data['total']))
        {
            return response()->json([
            'data' => "Data Not Found."], 404);
        }
        return response()->json([
            'data' => $data['data'],
            'total' => $data['total']],
            200);
    }

    public function getAcademicSetupById($id)
    {
        $data = $this->feesacademicsetupinterface->getAcademicSetupById($id);
        return response()->json([
            'data' => $data], 200);
    }

    public function updateAcademicSetup(FeesAcademicSetupUpdateFormRequest $request, $id)
    {
        $validatedData = $request->validated();
        $data = $this->feesacademicsetupinterface->updateAcademicSetup($validatedData, $id);
        return response()->json([
            'data' => $data], 200);
    }

    public function getStudentAcademicFees(Request $request)
    {
        $data = $this->feesacademicsetupinterface->getStudentAcademicFees($request);
        if(!empty($data)){
            return response()->json([
                'data' => $data],
                200);
        }
        return response()->json([
            'data' => "Data Not Found."], 404);
    }

    public function getFeesDashboard(Request $request)
    {
        $data = $this->feesacademicsetupinterface->getFeesDashboard($request);
        if(!empty($data)){
            return response()->json([
                'data' => $data],
                200);
        }
        return response()->json([
            'data' => "Data Not Found."], 404);
    }
}
