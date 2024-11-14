<?php

namespace App\Repositories;

use App\Models\TransportRouteStop;
use App\Interfaces\TransportRouteStopInterface;
use Illuminate\Http\Request;

class TransportRouteStopRepository implements TransportRouteStopInterface
{
    public function getAllStopsByRouteId( $route_id, Request $request, $limit = 10)
    {
        $query = TransportRouteStop::query()
            ->where('status', 1)
            ->where('route_id', $route_id)
            ->withoutTrashed();

        $total = $query->count();
        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where('stop_name', 'like', "%{$search}%");
        }
        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'asc');
        }
        $stops = $query->paginate($limit);

        return [
            'data' => $stops->items(),
            'total' => $total
        ];
    }

    public function getStopById($id)
    {
        return TransportRouteStop::where('transport_route_stops.id', $id)
        ->join('transport_routes', 'transport_routes.id', '=', 'transport_route_stops.route_id')
        ->select('transport_route_stops.*', 'transport_routes.start_latitude','transport_routes.end_latitude', 'transport_routes.start_logitude', 'transport_routes.end_logitude')->first();
    }

    public function createStop($data)
    {
        return TransportRouteStop::create($data);
    }

    public function updateStop($id, $data)
    {
        $stop = $this->getStopById($id);
        if (!$stop) {
            return null;
        }
        $stop->update($data);
        return $stop;
    }

    public function deleteStop($id)
    {
        $stop = $this->getStopById($id);
        if (!$stop) {
            return false;
        }
        $stop->delete();
        return true;
    }
}
