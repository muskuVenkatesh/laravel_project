<?php

namespace App\Http\Controllers\API;

use App\Models\Departments;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\DepartmentInterface;
use App\Http\Requests\DepartmentRequest;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\DepartmentupdateRequest;

class DepartmentsController extends Controller
{
    protected $departmentInterface;

    public function __construct(DepartmentInterface $departmentInterface)
    {
        $this->departmentInterface = $departmentInterface;
    }

    public function getDepartment(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $alldepartment = $this->departmentInterface->getDepartment($request, $perPage);
        if($alldepartment['data'])
        {
            return response()->json([
                'data' => $alldepartment['data'],
                'total' => $alldepartment['total']
            ], 200);
        }
        else  {
            throw new DataNotFoundException('Departments Data Not Found');
        }
    }

    public function createDepartment(DepartmentRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        try {

            $validatedData = $request->validated();
            $data = $this->departmentInterface->createDepartment($validatedData);
            return response()->json([
                'data' => $data,
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

    public function getDepartmentByID($id)
    {
        $data = Departments::find($id);
        if ($data) {
            return response()->json([
                'data' => $data,
            ], 200);
        }else {
            throw new DataNotFoundException('Departments Data Not Found');
        }
    }

    public function updateDepartment(DepartmentupdateRequest $request , $id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        try {
            $validatedData = $request->validated();
            $department = Departments::find($id);
            if (!$department) {
                throw new DataNotFoundException('Departments Data Not Found');
            }
            $department = $this->departmentInterface->updateDepartment($validatedData, $id);

            return response()->json([
                'data' => $department,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

    }

    public function deleteDepartment($id)
    {
        // if (!get_permission('delete', Schools::class)) {
        //     return response()->json(['status' => 'access_denied'], 403);
        // }
        if($id)
        {
            $department = $this->departmentInterface->deleteDepartment($id);
            if (!$department) {
                return response()->json([
                    'message' => 'Departments not found',
                ], 404);
            }
            $department->delete();
            $department->status = 0;
            $department->save();
            return response()->noContent();
        }
    }
}
