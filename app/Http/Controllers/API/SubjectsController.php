<?php

namespace App\Http\Controllers\API;

use App\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\SubjectsInterface;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\SubjectsEditRequest;
use App\Http\Requests\SubjectsCreateRequest;

class SubjectsController extends Controller
{
    protected $subjectInterface;

    public function __construct(SubjectsInterface $subjectInterface)
    {
        $this->subjectInterface = $subjectInterface;
    }

    public function getSubject(Request $request)
    {
        $perPage = $request->input('_limit',10);
        $allsubject = $this->subjectInterface->getSubject($request, $perPage);
        if(!empty($allsubject['data']))
        {
            return response()->json([
                'data' => $allsubject['data'],
                'total' => $allsubject['total']
            ], 200);
        }
        else  {
            throw new DataNotFoundException('No Subject Data Found');
        }
    }

    public function createSubject(SubjectsCreateRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        try {
            $validatedData = $request->validated();
            $response = $this->subjectInterface->createSubject($validatedData);
            if ($response['status'] === 'error') {
                return response()->json(['message' => $response['message']], $response['code']);
            }
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'data' => $response['data'],
                'token' => $token,
            ], $response['code']);

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

    public function getSubjectByID($id)
    {
        $data = Subjects::find($id);
        if (!$data) {
            throw new DataNotFoundException('No Subject found');
        }else {
            return response()->json([
                'data' => $data,
            ], 201);
        }
    }

    public function updateSubject(SubjectsEditRequest $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        try {
            $validatedData = $request->validated();
            $subject = Subjects::find($id);
            if (!$subject) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Subject not found',
                ], 404);
            }
            $subjectResponse = $this->subjectInterface->updateSubject($validatedData, $id);
            if ($subjectResponse['status'] === 'error') {
                return response()->json([
                    'status' => 'error',
                    'message' => $subjectResponse['message'],
                ], $subjectResponse['code']);
            }

            return response()->json([
                'status' => 'success',
                'data' => $subjectResponse['data'],
                'message' => 'Subject updated successfully.',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during the update',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function deleteSubject($id)
    {
        if($id)
        {
            $subject = $this->subjectInterface->deleteSubject($id);
            if (!$subject) {
                return response()->json([
                    'message' => 'Subject not found',
                ], 404);
            }
            $subject->delete();
            $subject->status = 0;
            $subject->save();
            return response()->json(['message'=> 'Subject Deleted Successfully']);
        }
    }

    public function getSubjectTypes()
    {
        $subjectTypes = $this->subjectInterface->getSubjectTypes();
        if (count($subjectTypes) === 0) {
            throw new DataNotFoundException('No Subject found');
        }
        return response()->json([
            'data' => $subjectTypes,
        ], 200);
    }
}
