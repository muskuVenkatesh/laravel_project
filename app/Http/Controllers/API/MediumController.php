<?php

namespace App\Http\Controllers\API;

use App\Models\Medium;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Interfaces\MediumInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MediumEditRequest;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\MediumCreateRequest;

class MediumController extends Controller
{
    protected $mediumInterface;

    public function __construct(MediumInterface $mediumInterface)
    {
        $this->mediumInterface = $mediumInterface;
    }

    public function getMedium(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $allsubject = $this->mediumInterface->getMedium($request, $perPage);
        if(empty($allsubject['data']) || empty($allsubject['total']) )
        {
            throw new DataNotFoundException('Medium Reacords Not Found');
        }
        else  {
            return response()->json([
                'data' => $allsubject['data'],
                'total' => $allsubject['total']
            ], 200);
        }
    }

    public function createMedium(MediumCreateRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        try {

            $validatedData = $request->validated();
            $data = $this->mediumInterface->createMedium($validatedData);
            $token = JWTAuth::fromUser($user);
            return response()->json([
                'data' => $data,
                'token' => $token,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getMediumByID($id)
    {
        $data = Medium::find($id);
        if (!$data) {
            return response()->json([
                'message' => 'No Data Found'
            ]);
        }
        else
        { return response()->json([
                'data' => $data,
            ], 201);
        }
    }

    public function updateMedium(MediumEditRequest $request , $id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        try {
            $validatedData = $request->validated();
            $token = JWTAuth::fromUser($user);
            $subject = Medium::find($id);
            if (!$subject) {
                return response()->json([
                    'message' => 'Medium not found',
                ], 404);
            }
            $subject = $this->mediumInterface->updateMedium($validatedData, $id);

            return response()->json([
                'data' => $subject,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

    }

    public function deleteMedium($id)
    {
        if($id)
        {
            $medium = $this->mediumInterface->deleteMedium($id);
            if (!$medium) {
                return response()->json([
                    'message' => 'Medium not found',
                ], 404);
            }
            $medium->delete();
            $medium->status = 0;
            $medium->save();
            return response()->noContent();
        }
    }
}
