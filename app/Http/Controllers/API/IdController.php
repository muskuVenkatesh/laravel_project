<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interfaces\IdInterface;
use Illuminate\Http\Request;
use App\Http\Requests\IdRequest;
use App\Http\Requests\updaterequestidcard;

class IdController extends Controller
{
    protected $idinterface;
    public function __construct(IdInterface $idinterface) 
    {
        $this->idinterface = $idinterface;
    }

    public function CreateId(IdRequest $request)
    {
        $validatedData = $request->validated();
        $id = $this->idinterface->CreateId($validatedData);
        return response()->json([
            'message' => 'created successfully',
        ], 201);
    }

    public function GetAllId(Request $request, $id_type) 
    {
        $limit = $request->input('_limit', 10); 
        $id = $this->idinterface->GetAllId($request, $limit, $id_type); 
        if ($id['total'] === 0) {
            return response()->json([
                'message' => 'Data not found.'
            ], 404);
        }
        return response()->json([
            'data'  => $id['data'],
            'total' => $id['total']
        ], 200);
    }

    public function getIdCardById($id)
    {
        $idCard = $this->idinterface->getIdCardById($id);
        if (empty($idCard)) {
            return response()->json([
                'message' => 'Data not found.'
            ], 404); 
        }
        return response()->json($idCard, 200);
    }

    public function updateIdCard(updaterequestidcard $request, $id)
    {
        $validatedData = $request->validated();
        $updatedIdCard = $this->idinterface->updateIdCard($id, $validatedData);
        if (!$updatedIdCard) {
            return response()->json([
                'message' => 'Data not found or update failed.'
            ], 404); 
        }
        return response()->json([
            'message' => 'Updated successfully',
            'data' => $updatedIdCard
        ], 200);
    }

    public function softDeleteIdCard($id)
    {
        $deletedIdCard = $this->idinterface->softDeleteIdCard($id);
        if ($deletedIdCard) {
            return response()->json([
                'message' => 'ID card soft deleted successfully.',
            ], 200);
        }
        return response()->json([
            'message' => 'ID card not found.',
        ], 404);
    }
}