<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TransportRepository;
use App\Http\Requests\TransportDetailRequest;
use App\Http\Requests\UpdateTransportDetailsRequest;
use App\Interfaces\TransportInterface;
use App\Exceptions\DataNotFoundException;

class TransportController extends Controller
{
    protected $transportinterface;

    public function __construct(TransportInterface $transportinterface)
    {
        $this->transportinterface = $transportinterface;
    }

    public function  CreateTransport(TransportDetailRequest $request)
    {
        $Remarks = $this->transportinterface->CreateTransport($request->validated());
        return response()->json([
            'message' => $Remarks['message'],
        ], $Remarks['code']);
    }

    public function GetAllTransportDetails(Request $request)
    {
        $transportdetails = $this->transportinterface->GetAllTransportDetails($request);
        if(empty($transportdetails['data']) || empty($transportdetails['total']))
        {
            throw new DataNotFoundException('Transport details not found.');
        }
        return response()->json([
            'data'  => $transportdetails['data'],
            'total' => $transportdetails['total']],
        200);
    }

    public function GetTransportDetailsById($id)
    {
        $trsnaportdetails = $this->transportinterface->GetTransportDetailsById($id);
        if (!$trsnaportdetails) {
            return response()->json(['message' => 'Transport Details not found'], 404);
        }
        return response()->json($trsnaportdetails, 200);
    }

    public function UpdateTransportDetails(UpdateTransportDetailsRequest $request, $id)
    {
        $updatedtransportdetails = $this->transportinterface->UpdateTransportDetails($id, $request->validated());
        if (!$updatedtransportdetails) {
            return response()->json(['message' => 'Transport Details not found'], 404);
        }
        return response()->json([
            'data' => $updatedtransportdetails,
        ], 200);
    }

    public function DeleteTransportDetails($id)
    {
        $DeletedTransportDetails = $this->transportinterface->DeleteTransportDetails($id);
        if (!$DeletedTransportDetails) {
            return response()->json(['message' => 'Transport Details not found'], 404);
        }
        return response()->json(['message' => 'Transport Details Deleted successfully'], 200);
    }

    public function getTransportDetailsByStudentId(Request $request)
    {
        $student_id = $request->query('student_id');
        if (!$student_id) {
            return response()->json(['message' => 'Student ID is required'], 400);
        }
        $transportDetails = $this->transportinterface->getTransportDetailsByStudentId($student_id);
        if (!$transportDetails) {
            return response()->json(['message' => 'No transport details found for the given student'], 404);
        }
        return response()->json(['data' => $transportDetails], 200);
    }

}
