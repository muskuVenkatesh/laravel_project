<?php

namespace App\Http\Controllers\API;

use App\Models\FeesAcademicPayments;
use Illuminate\Http\Request;
use App\Interfaces\FeesAcademicPaymentsInterface;
use App\Http\Requests\FeesAcademicPaymentRequest;
use App\Http\Controllers\Controller;

class FeesAcademicPaymentsController extends Controller
{
    protected $feesacademicpaymentsinterface;

    public function __construct(FeesAcademicPaymentsInterface $feesacademicpaymentsinterface)
    {
        $this->feesacademicpaymentsinterface = $feesacademicpaymentsinterface;
    }

    public function createAcademicPayments(FeesAcademicPaymentRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->feesacademicpaymentsinterface->createAcademicPayments($validatedData);
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function getAcademicPayments(Request $request)
    {
        $allpayment = $this->feesacademicpaymentsinterface->getAcademicPayments($request);
        if($allpayment['data'])
        {
            return response()->json([
                'data' => $allpayment['data'],
                'total' => $allpayment['total']
            ], 200);
        }
        return response()->json([
            'data' => "No More Payments"
        ], 404);
    }

    public function getAcademicPaymentsMethod(Request $request)
    {
        $allpayment = $this->feesacademicpaymentsinterface->getAcademicPaymentsMethod($request);
        if($allpayment->isNotEmpty())
        {
            return response()->json([
                'data' => $allpayment
            ], 200);
        }
        return response()->json([
            'data' => "No More Payments"
        ], 404);
    }
}
