<?php

namespace App\Http\Controllers\API;
use App\Models\Occupation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\OccupationInterface;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\OccupationEditRequest;
use App\Http\Requests\OccupationCreateRequest;

class OccupationController extends Controller
{
    protected $occupationInterface;

    public function __construct(OccupationInterface $occupationInterface)
    {
        $this->occupationInterface = $occupationInterface;
    }

    public function createOccupation(OccupationCreateRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->occupationInterface->storeOccupations($validatedData);

        return response()->json([
            'data' => $data
        ], 201);
    }

    public function getOccupations(Request $request)
    {
        $search = $request->input('q');
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
        $perPage = $request->input('per_page', 15);

        $data = $this->occupationInterface->getOccupations($search, $sortBy, $sortOrder, $perPage);

        if(empty($data['data']) || empty($data['total'])) {
            throw new DataNotFoundException('Data Not Found');
        } else {
            return response()->json([
                'occupations' => $data['data'],
                'total' => $data['total']
            ],200); 
        }
    }

    public function getSingleOccupation($id)
    {
        $data = Occupation::find($id);

        if ($data) {
            return response()->json([
                'data' => $data
            ],200);
        } else {
            return response()->json([
                'data' => "No data Found."
            ],404);
        }
    }

    public function updateOccupations(OccupationEditRequest $request, $id)
    {
        $validatedData = $request->validated();
        $data = $this->occupationInterface->updateOccupations($validatedData, $id);

        return response()->json([
            'data' => $data
        ], 201);
    }

    public function deleteOccupation($id)
    {
        $data = $this->occupationInterface->deleteOccupations($id);
        if ($data) {
            return response()->noContent();
        }
        return response()->json([
            'data' => 'Unable to delete the occupation.'
        ], 400);
    }
}
