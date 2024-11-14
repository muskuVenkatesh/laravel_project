<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\TransportRouteStopRequest;
use App\Interfaces\TransportRouteStopInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\DataNotFoundException;

class TransportRouteStopController extends Controller
{
    protected $repository;

    public function __construct(TransportRouteStopInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllStopsByRouteId(Request $request)
    {
        $routeId = $request->input('route_id');
        $perPage = $request->input('_limit', 10);
        if (!$routeId) {
            return response()->json(['error' => 'route_id is required.'], 400);
        }
        $stops = $this->repository->getAllStopsByRouteId($routeId, $request, $perPage);
        if (empty($stops['data'])) {
            throw new DataNotFoundException('No Stops Found for the specified Route.');
        }

        $stopData = [];
        foreach ($stops['data'] as $stopItem) {
            $stopData = array_merge($stopData, array_map(function($stop) use ($stopItem) {
                return [
                    'id' => $stopItem['id'],
                    'stop_id' => $stop['stop_id'],
                    'pickup_point' => $stop['pickup_point'],
                    'pickup_time' => $stop['pickup_time'],
                    'drop_time' => $stop['drop_time'],
                    'pickup_latitude' => $stop['pickup_latitude'],
                    'pickup_longitude' => $stop['pickup_longitude'],
                    'drop_latitude' => $stop['drop_latitude'],
                    'drop_longitude' => $stop['drop_longitude'],
                    'pickup_distance' => $stop['pickup_distance'],
                    'amount' => $stop['amount']
                ];
            }, $stopItem['stop_data']));
        }
        return response()->json([
            'data' => $stopData,
            'total' => count($stopData)
        ], 200);
    }

    public function createStop(TransportRouteStopRequest $request)
    {
        $validatedData = $request->validated();
        $this->repository->createStop($validatedData);
        return response()->json([
            'message' => 'Stop created successfully.'
        ], 201);
    }

    public function getStopById(int $id)
    {
        $stop = $this->repository->getStopById($id);
        if (!$stop) {
            return response()->json([
                'message' => 'Stop not found.',
            ], 404);
        }
        return response()->json([
            'data' => $stop,
        ], 200);
    }

    public function updateStop(TransportRouteStopRequest $request, int $id)
    {
        $stop = $this->repository->getStopById($id);

        if (!$stop) {
            return response()->json([
                'message' => 'Stop not found.',
            ], 404);
        }
        $validatedData = $request->validated();
        $updatedStop = $this->repository->updateStop($id, $validatedData);

        return response()->json([
            'message' => 'Stop updated successfully.',
            'data' => $updatedStop,
        ], 200);
    }

    public function deleteStop($id)
    {
        $stop = $this->repository->getStopById($id);
        if (!$stop) {
            return response()->json([
                'message' => 'Stop not found.',
            ], 404);
        }
        $this->repository->deleteStop($id);

        return response()->json([
            'message' => 'Stop deleted successfully.',
        ], 200);
    }
}
