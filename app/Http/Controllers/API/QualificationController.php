<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Qualifications;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\QualificationInterface;
use App\Http\Requests\QualificationEditRequest;
use App\Http\Requests\QualificationCreateRequest;

class QualificationController extends Controller
{
    protected $qualificationinterface;

    public function __construct(QualificationInterface $qualificationinterface)
    {
        $this->qualificationinterface = $qualificationinterface;
    }

    public function createQualification(QualificationCreateRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->qualificationinterface->StoreQualifications($validatedData);

        return response()->json([
            'data' => $validatedData
        ], 201);
    }

    public function getQualifications(Request $request)
    {
        $search = $request->input('q');
        $sortBy = $request->input('_sort', 'name');
        $sortOrder = $request->input('_order', 'asc');
        $perPage = $request->input('_limit', 10);
        $data = $this->qualificationinterface->getQualifications($search, $sortBy, $sortOrder, $perPage);
        if(empty($data['data']) || empty($data['total']))
        {
          throw new DataNotFoundException('No Qualification Data Found.');
        }
        else
        {
            return response()->json([
                'qualifications' => $data['data'],
                'total' => $data['total']
              ],200);
        }
    }

    public function GetSingleQualification($id)
    {
        $data = Qualifications::find($id);

        if($data)
        {
            return response()->json([
                'data' => $data
            ]);
        }
        else
        {
            throw new DataNotFoundException('No Data Found.');
        }
    }

    public function updateQualification(QualificationEditRequest $request, $id)
    {
        $validatedData = $request->validated();
        $data = $this->qualificationinterface->UpdateQualifications($validatedData, $id);

        return response()->json([
            'data' => $data
        ], 201);
    }

    public function DeleteQualification($id)
    {
        $data = $this->qualificationinterface->deleteQualifications($id);
        if($data)
        {
            return response()->noContent();
        }
    }
}
