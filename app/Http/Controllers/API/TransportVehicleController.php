<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Interfaces\TransportVehicleInterface;
use Illuminate\Http\Request;

class TransportVehicleController extends Controller
{
    protected TransportVehicleInterface $transportvehicleinterface;

    public function __construct(TransportVehicleInterface $transportvehicleinterface)
    {
        $this->transportvehicleinterface = $transportvehicleinterface;
    }

    public function createTransportVehicle(StoreVehicleRequest $request)
    {
        $this->transportvehicleinterface->createTransportVehicle($request->validated());

        return response()->json([
            'message' => 'Created successfully',
        ], 200);
    }

    public function getAllTransportVehicle(Request $request)
    {
        $transportvehicle = $this->transportvehicleinterface->getAllTransportVehicle($request);

        if (empty($transportvehicle['data']) || empty($transportvehicle['total'])) {
            return response()->json([
                'message' => 'Data Not Found.',
            ], 404);
        }

        return response()->json([
            'data' => $transportvehicle['data'],
            'total' => $transportvehicle['total'],
        ], 200);
    }

    public function getTransportVehicle($id)
    {
        $transportvehicle = $this->transportvehicleinterface->GetTransportVehicle($id);

        if ($transportvehicle) {
            return response()->json([
                'data' => $transportvehicle,
            ], 200);
        }

        return response()->json([
            'message' => 'Transport Vehicle not found',
        ], 404);
    }

    public function updateTransportVehicle(UpdateVehicleRequest $request, $id)
    {
        $updatedTransportVehicle = $this->transportvehicleinterface->updateTransportVehicle($id, $request->validated());

        if ($updatedTransportVehicle) {
            return response()->json([
                'message' => 'Transport Vehicle updated successfully',
            ], 200);
        }

        return response()->json([
            'message' => 'Transport Vehicle not found',
        ], 404);
    }

    public function deleteTransportVehicle($id)
    {
        $deletedTransportVehicles = $this->transportvehicleinterface->deleteTransportVehicle($id);

        if ($deletedTransportVehicles) {
            return response()->json([
                'message' => 'Transport Vehicle deleted successfully',
            ], 200);
        }

        return response()->json([
            'message' => 'Transport Vehicle Not Found',
        ], 404);
    }
}
