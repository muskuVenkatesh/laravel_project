<?php

namespace App\Http\Controllers\API;

use App\Models\Parents;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ParentRepository;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\StoreRequets\StoreParentRequest;
use App\Http\Requests\UpdateRequests\UpdateParentRequest;

class ParentController extends Controller
{
    protected $parentRepository;
    public function __construct(ParentRepository $parentRepository){
        $this->parentRepository =$parentRepository;
    }
    public function CreateParent(StoreParentRequest $request)
    {
        $validatedData = $request->validated();
        $parent = $this->parentRepository->store($validatedData);
        return response()->json([
            'parent' => $validatedData
        ], 201);

    }

    public function GetParents(Request $request)
    {
        $validatedData = $request->validate([
            'branch_id' => 'required|integer',
            'class_id' => 'required|integer',
            'section_id' => 'required|integer'
        ]);

        $search = $request->input('q');
        $sortBy = $request->input('sort_by', 'first_name');
        $sortOrder = $request->input('sort_order', 'asc');
        $perPage = $request->input('per_page', 15);
        $parent = $this->parentRepository->Getall($validatedData, $search, $sortBy, $sortOrder, $perPage);
        if (empty($parent['items'])){
            throw new DataNotFoundException('Data Not Found');
        }

        return response()->json([
            'parents' => $parent['items'],
            'total' => $parent['total']
        ]);
    }

    public function GetSingleParent($id)
    {

        $parent = $this->parentRepository->show($id);

        if ($parent) {
            return response()->json(['parent' => $parent], 200);
        } else {
            return response()->json(['error' => 'Parent not found'], 404);
        }
    }

    public function getStudentByParent(Request $request)
    {
        $parent_id = $request->input('parent_id');
        $student = $this->parentRepository->getStudentByparent($parent_id);
        if ($student && count($student) > 0) {
            return response()->json([
                'data' => $student
            ], 200);
        }
        throw new DataNotFoundException('Student Not Found For this Parent');
    }

    public function updateParent(UpdateParentRequest $request, $id)
    {

        $validatedData = $request->validated();
        $parent = $this->parentRepository->updateParent($id,$validatedData);
        return response()->noContent();
    }

    public function DeleteParent($id){
        $parent = $this->parentRepository->destroy($id);
        return response()->noContent();
    }

    public function getParentBynumber($num)
    {
        $data = Parents::where('phone', $num)->first();
        if (!$data) {
            throw new DataNotFoundException('No Parent Found By Number');
        }
        $userDetails = UserDetails::where('user_id', $data->user_id)->first(['date_of_birth', 'gender', 'image', 'aadhaar_card_no', 'pan_card_no']);
        $data = $data->toArray();
        if($userDetails)
        {
            $userDetailsArray = $userDetails->toArray();
            $mergedData = array_merge($data, $userDetailsArray);
            return response()->json([
                'data' => $mergedData
            ]);
        }
        else
        {
            throw new DataNotFoundException('User details not found.');
        }
    }
}

