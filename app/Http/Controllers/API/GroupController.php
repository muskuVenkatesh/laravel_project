<?php

namespace App\Http\Controllers\API;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Interfaces\GroupInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupEditRequest;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\GroupCreateRequest;

class GroupController extends Controller
{
    protected $groupinterface;

    public function __construct(GroupInterface $groupinterface)
    {
        $this->groupinterface = $groupinterface;
    }

    public function createGroup(GroupCreateRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->groupinterface->StoreGroups($validatedData);

        return response()->noContent();
    }

    public function getGroups(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $branchId = $request->input('branch_id');
        $banch = Group::where('branch_id' , $branchId)->first();
        if($banch)
        {
            $allgroup = $this->groupinterface->getGroups($request, $perPage, $branchId);
            return response()->json([
                'data' => $allgroup['data'],
                'total' => $allgroup['total']
            ], 200);
        }
        else  {
            throw new DataNotFoundException('Reacords Not Found');
        }
    }

    public function GetSingleGroup($id)
    {
        $data = Group::find($id);
        if($data){
            return response()->json([
                'data' => $data
            ]);
        }else {
            throw new DataNotFoundException('Records Not Found');
        }
    }

    public function updateGroup(GroupEditRequest $request, $id)
    {
        $validatedData = $request->validated();
        $data = $this->groupinterface->UpdateGroup($validatedData, $id);
        return response()->noContent();
    }

    public function DeleteGroup($id)
    {
        $data = $this->groupinterface->deleteGroup($id);
        if($data){
            return response()->json(['message'=>'Group Deleted SUccessfully']);
        }
    }
}
