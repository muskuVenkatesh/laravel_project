<?php

namespace App\Http\Controllers\API;

use App\Models\Schools;
use App\Models\Branches;
use App\Jobs\BranchProcess;
use App\Jobs\SchoolProcess;
use Illuminate\Http\Request;
use App\Imports\SchoolImport;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Interfaces\SchoolInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SchoolEditRequest;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\SchoolCreateRequest;


class SchoolController extends Controller
{
    protected $schoolInterface;

    public function __construct(SchoolInterface $schoolinterface)
    {
        $this->schoolinterface = $schoolinterface;
    }

    public function CreateSchool(SchoolCreateRequest $request): JsonResponse
    {

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        try {

            $validatedData = $request->validated();
            $school = $this->schoolinterface->CreateSchool($validatedData);
            return response()->json([
                'message' => 'School created successfully',
                'school' => $school,
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


    public function GetSingleSchool($id): JsonResponse
    {
        if(!empty($id))
        {
            $school_data = $this->schoolinterface->GetSchool($id);
            if (!$school_data) {
                return response()->json([
                    'message' => 'School not found',
                ], 404);
            }
            if($school_data){
                return response()->json([
                    'school' => $school_data,
                ], 201);
            }
            else
            {
                return response()->json([
                    'message' => 'Invalid ID passing',
                ], 422);
            }
        }
        else
        {
            return response()->json([
                'message' => 'No ID found',
            ], 422);
        }
    }

    public function UpdateSchool(SchoolEditRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        try {
            $validatedData = $request->validated();
            $schoolId = $request->input('school_id');
            $school = Schools::find($schoolId);
            if (!$school) {
                return response()->json([
                    'message' => 'School not found',
                ], 404);
            }
            $school = $this->schoolinterface->UpdateSchool($schoolId, $validatedData);

            return response()->json([
                'update' => $school,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function GetAllSchool(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $allschool = $this->schoolinterface->GetAllSchool($request, $perPage);
        if(empty($allschool['data']) || empty($allschool['total']))
        {
            throw new DataNotFoundException('No School Data Found.');
        }
        else{
            return response()->json([
                'data' => $allschool['data'],
                'total' => $allschool['total']
            ], 200);
        }
    }

    public function DeleteSchool($id)
    {
        if($id)
        {
            $school = $this->schoolinterface->DeleteSchool($id);
            if (!$school) {
                throw new DataNotFoundException('School Not Found.');
            }
            $school->delete();
            $school->status = 0;
            $school->save();
            foreach ($school->branches as $branch) {
                $branch->status = 0;
                $branch->save();
            }
            return response()->json([
                'message' => "School Deleted Successfully"
            ], 200);
        }
    }

    public function GetCurrencyType()
    {
        $data = 'No Data Found';
        $currency = $this->schoolinterface->GetCurrencyType();
        if(!empty($currency))
        {
            $data = $currency;
        }
        return response()->json([
            'data' => $data
        ]);
    }

    public function UploadFile(Request $request)
    {
        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $fileContent = file_get_contents($file->getRealPath());
            $base64File = base64_encode($fileContent);
            SchoolProcess::dispatch($base64File, $file->getClientOriginalName());
            return response()->json(['message' => 'Data import has been queued']);
        }
        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function UploadBranchFile(Request $request)
    {
        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $fileContent = file_get_contents($file->getRealPath());
            $base64File = base64_encode($fileContent);
            BranchProcess::dispatch($base64File, $file->getClientOriginalName());
            return response()->json(['message' => 'Data import has been queued']);
        }
        return response()->json(['message' => 'No file uploaded'], 400);
    }
}
