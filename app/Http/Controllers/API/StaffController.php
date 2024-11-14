<?php

namespace App\Http\Controllers\API;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Interfaces\StaffInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StaffEditRequest;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\StaffCreateRequest;

class StaffController extends Controller
{
    protected $staffinterface;

    public function __construct(StaffInterface $staffinterface)
    {
        $this->staffinterface = $staffinterface;
    }

    public function StoreStaff(StaffCreateRequest $request)
    {
        $validatedData = $request->validated();

       $data = $this->staffinterface->StoreStaff($validatedData);
       return response()->json([
            'data' => "Staff Create Successfully."
        ], 201);

    }

    public function GetAllStaff(Request $request)
    {
        $branchId = $request->input('branch_id');
        $search = $request->input('q');
        $sortBy = $request->input('_sort', 'first_name');
        $sortOrder = $request->input('_order', 'asc');
        $perPage = $request->input('_limit', 10);
        $staff = $this->staffinterface->GetAllStaff($branchId, $search, $sortBy, $sortOrder, $perPage);
        if(empty($staff['data']) || empty($staff['total']))
        {
            throw new DataNotFoundException('No Staff Data Found.');
        }
        else
        {
            return response()->json([
                'data' => $staff['data'],
                'total' => $staff['total']
            ], 200);
        }
    }

    public function GetStaffById($id)
    {
       $staffid = Staff::find($id);
       if($staffid)
       {
         $data = $this->staffinterface->GetStaffById($id);
       }
       else
       {
         $data = 'id is Invalied';
       }
       return response()->json([
           'data' => $data
       ], 200);
    }

    public function DestroyStaff($id)
    {
        $staffid = Staff::find($id);
       if($staffid)
        {
         $data = $this->staffinterface->DestroyStaff($id);
        }else
        {
         $data = 'id is Invalied';
        }
       return response()->json([
           'data' => $data
       ], 201);
    }

    public function UpdateStaff(StaffEditRequest $request, $id)
    {
        $validatedData = $request->validated();
        $data = $this->staffinterface->UpdateStaff($validatedData, $id);
        return response()->json([
            'data' => $validatedData
        ], 201);
    }
}
