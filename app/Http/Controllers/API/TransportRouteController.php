<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\TransportRouteRequest;
use App\Interfaces\TransportRouteInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\DataNotFoundException;

class TransportRouteController extends Controller
{
    protected $repository;

    public function __construct(TransportRouteInterface $repository)
    {
        $this->repository = $repository;
    }

    public function GetAllTransportRoutes(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $routes = $this->repository->getAllTransportRoutes($request, $perPage);
        if (empty($routes['data'])) {
            throw new DataNotFoundException('No Transport Routes Found.');
        }
        return response()->json([
            'data' => $routes['data'],
            'total' => $routes['total']
        ], 200);
    }

    public function CreateTransportRoute(TransportRouteRequest $request)
    {
        $validatedData = $request->validated();
        $this->repository->createTransportRoute($validatedData);
        return response()->json([
            'message' => 'Transport route created successfully.'
        ], 201);
    }

    public function GetTransportRouteById(int $id)
    {
        $route = $this->repository->getTransportRouteById($id);
        if (!$route) {
            return response()->json([
                'message' => 'Transport route not found.',
            ], 404);
        }
        return response()->json([
            'data' => $route,
        ], 200);
    }

    public function UpdateTransportRoute(TransportRouteRequest $request, $id)
    {
        $route = $this->repository->getTransportRouteById($id);
        if (!$route) {
            return response()->json([
                'message' => 'Transport route not found.',
            ], 404);
        }
        $validatedData = $request->validated();
        $updatedRoute = $this->repository->updateTransportRoute($id, $validatedData);
        return response()->json([
            'message' => 'Transport route updated successfully.',
            'data' => $updatedRoute,
        ], 200);
    }

    public function DeleteTransportRoute($id)
    {
        $route = $this->repository->getTransportRouteById($id);
        if (!$route) {
            return response()->json([
                'message' => 'Transport route not found.',
            ], 404);
        }
        $this->repository->deleteTransportRoute($id);
        return response()->json([
            'message' => 'Transport route deleted successfully.',
        ], 200);
    }

    public function GetRoutes(Request $request)
    {
        $route = $this->repository->getRoutes($request);
        if ($route->isEmpty()) {
            return response()->json([
                'message' => 'Transport route not found.',
            ], 404);
        }
        return response()->json([
            'data' => $route,
        ], 200);
    }

}
