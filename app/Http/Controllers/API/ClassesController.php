<?php

namespace App\Http\Controllers\API;

use App\Models\Classes;
use App\Models\Branches;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Interfaces\ClassesInterface;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\ClassesEditRequest;
use App\Http\Requests\ClassesCreateRequest;

class ClassesController extends Controller
{
    protected $classInterface;

    public function __construct(ClassesInterface $classInterface)
    {
        $this->classInterface = $classInterface;
    }

    public function getClass(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $allclass = $this->classInterface->getClass($request, $perPage);
        if (empty($allclass['data']) || $allclass['total'] == 0){
            throw new DataNotFoundException('No Classes Found.');
        }
        return response()->json([
            'data' => $allclass['data'],
            'total' => $allclass['total']
        ], 200);
    }

    public function createClass(ClassesCreateRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        try {
            $validatedData = $request->validated();
            $data = $this->classInterface->createClass($validatedData);
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

    public function getClassByID($id)
    {
        $data = Classes::find($id);
        if (!$data) {
            return response()->json([
                'message' => 'No Data Found'
            ]);
        }else {
            return response()->json([
                'data' => $data,
            ], 201);
        }
    }

    public function updateClass(ClassesEditRequest $request , $id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        try {
            $validatedData = $request->validated();
            $token = JWTAuth::fromUser($user);
            $classes = Classes::find($id);
            if (!$classes) {
                return response()->json([
                    'message' => 'Class not found',
                ], 404);
            }
            $classes = $this->classInterface->updateClass($validatedData, $id);

            return response()->json([
                'message' => 'Class Updated Successfully',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

    }

    public function deleteClass($id)
    {
        if($id)
        {
            $classes = $this->classInterface->deleteClass($id);
            if (!$classes) {
                return response()->json([
                    'message' => 'Class not found',
                ], 404);
            }
            $classes->delete();
            $classes->status = 0;
            $classes->save();
            return response()->json(['messge'=>'Class Deleted Successfully'],200);
        }
    }

    public function getClassesByBranch($branchId)
    {
        $branch = $this->classInterface->getClassesByBranch($branchId);
        if ($branch) {
            $classes = $branch->classes->map(function ($class) use ($branch) {
                $class['branch_name'] = $branch->branch_name;
                $class['branch_code'] = $branch->branch_code;
                return $class;
            });
            return response()->json([
                'classes' => $classes
            ]);
        }else {
            throw new DataNotFoundException('No Classes Found For The Branch.');
        }
    }
}
