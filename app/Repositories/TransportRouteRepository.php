<?php

namespace App\Repositories;

use App\Models\TransportRoute;
use App\Interfaces\TransportRouteInterface;
use Illuminate\Http\Request;

class TransportRouteRepository implements TransportRouteInterface
{
    public function getAllTransportRoutes(Request $request, $limit = 10)
    {
        $branch_id = $request->input('branch_id');
        $query = TransportRoute::where('branch_id', $branch_id)->withoutTrashed();

        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('start_point', 'like', "%{$search}%")
                ->orWhere('end_point', 'like', "%{$search}%");
            });
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'asc');
        }

        $limit = $request->input('_limit', 10);
        $routes = $query->paginate($limit);
        return [
            'data' => $routes->items(),
            'total' => $query->count()
        ];
    }

    public function getTransportRouteById($id)
    {
        return TransportRoute::find($id);
    }

    public function createTransportRoute($data)
    {
        return TransportRoute::create($data);
    }

    public function updateTransportRoute($id, $data)
    {
        $route = $this->getTransportRouteById($id);
        if (!$route) {
            return null;
        }
        $route->update($data);
        return $route;
    }

    public function deleteTransportRoute($id)
    {
        $route = $this->getTransportRouteById($id);

        if (!$route) {
            return false;
        }
        $route->delete();
        return true;
    }

    public function getRoutes(Request $request)
    {
        $branch_id = $request->input('branch_id');
        $query = TransportRoute::query()
            ->selectRaw('id, (start_point || \' - \' || end_point) as route')
            ->where('status', 1)
            ->where('branch_id', $branch_id)
            ->withoutTrashed()
            ->get();
        return $query;
    }
}
