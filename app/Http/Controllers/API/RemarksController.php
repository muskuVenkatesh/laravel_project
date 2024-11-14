<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RemarksRepository;
use App\Http\Requests\RemarksRequest;
use App\Http\Requests\RemarksEditRequest;
use App\Interfaces\RemarksInterface;
use App\Exceptions\DataNotFoundException;

class RemarksController extends Controller
{
    protected $remarksinterface;

    public function __construct(RemarksInterface $remarksinterface)
    {
        $this->remarksinterface = $remarksinterface;
    }
    public function  CreateRemarks(RemarksRequest $request)
    {
        $Remarks = $this->remarksinterface->CreateRemarks($request->validated());
        return response()->json([
            'message' => 'created successfully',
        ], 200);
    }

    public function GetAllRemarks(Request $request)
    {
        $remarks = $this->remarksinterface->GetAllRemarks($request);
        if(empty($remarks['data']) || empty($remarks['total']))
        {
            throw new DataNotFoundException('Remark not found.');
        }
        return response()->json([
            'data'  => $remarks['data'],
            'total' => $remarks['total']],
        200);
    }

    public function GetRemarkById($id)
    {
        $remark = $this->remarksinterface->GetRemarkById($id);
        if (!$remark) {
            return response()->json(['message' => 'Remark not found'], 404);
        }
        return response()->json($remark, 200);
    }

    public function UpdateRemarks(RemarksEditRequest $request, $id)
    {
        $updatedRemark = $this->remarksinterface->UpdateRemarks($id, $request->validated());
        if (!$updatedRemark) {
            return response()->json(['message' => 'Remark not found'], 404);
        }
        return response()->json([
            'data' => $updatedRemark,
        ], 200);
    }

    public function SoftDeleteRemarks($id)
    {
        $softDeleted = $this->remarksinterface->SoftDeleteRemarks($id);
        if (!$softDeleted) {
            return response()->json(['message' => 'Remark not found'], 404);
        }
        return response()->json(['message' => 'deleted successfully'], 200);
    }
}
