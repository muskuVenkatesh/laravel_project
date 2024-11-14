<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTransportPilotRequest;
use App\Http\Requests\UpdateTransportPilotRequest;
use App\Interfaces\PilotInterface;
use Illuminate\Http\Request;

class PilotController extends Controller
{
    protected $pilotinterface;


    public function __construct(PilotInterface $pilotinterface)
    {
        $this->pilotinterface = $pilotinterface;
    }

    public function createPilot(CreateTransportPilotRequest $request)
    {
        $this->pilotinterface->createPilot($request->validated());

        return response()->json([
            'message' => 'Created successfully',
        ], 200);
    }

    public function getAllPilot(Request $request)
    {
        $pilot = $this->pilotinterface->getAllPilot($request);

        if (empty($pilot['data']) || empty($pilot['total'])) {
            return response()->json([
                'message' => 'Data Not Found.',
            ], 404);
        }

        return response()->json([
            'data' => $pilot['data'],
            'total' => $pilot['total'],
        ], 200);
    }

    public function getAllPilotbyid($id)
    {
        $pilot = $this->pilotinterface->getAllPilotbyid($id);

        if ($pilot) {
            return response()->json([
                'data' => $pilot,
            ], 200);
        }

        return response()->json([
            'message' => 'Pilot not found',
        ], 404);
    }

    public function updatePilot(UpdateTransportPilotRequest $request, $id)
    {
        $updatedpilot = $this->pilotinterface->updatePilot($id, $request->validated());

        if ($updatedpilot) {
            return response()->json([
                'message' => 'Transport Vehicle updated successfully',
            ], 200);
        }

        return response()->json([
            'message' => 'Transport Vehicle not found',
        ], 404);
    }

    public function deletepilot($id)
    {
        $deletedpilot = $this->pilotinterface->deletepilot($id);

        if ($deletedpilot) {
            return response()->json([
                'message' => 'Pilot deleted successfully',
            ], 200);
        }

        return response()->json([
            'message' => 'Pilot Not Found',
        ], 404);
    }
}
