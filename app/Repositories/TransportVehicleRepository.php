<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\TransportVehicleDetails;
use App\Interfaces\TransportVehicleInterface;

class TransportVehicleRepository implements TransportVehicleInterface
{
    protected $transportvehicles;

    public function __construct(TransportVehicleDetails $transportvehicles)
    {
        $this->transportvehicles = $transportvehicles;
    }

    public function createTransportVehicle($data)
    {
        $createdtransportvehicles = $this->transportvehicles->createTransportVehicle($data);
        return $createdtransportvehicles;
    }

    public function getAllTransportVehicle(Request $request)
    {
        $limit = $request->input('_limit', 10);
        $branch_id = $request->input('branch_id');

        $alltransportvehicle = TransportVehicleDetails::query()
            ->where('branch_id', $branch_id)
            ->where('status', 1);

        if ($request->has('q')) {
            $search = $request->input('q');
            $alltransportvehicle->where(function ($query) use ($search) {
                $query->where('vehicle_type', 'like', "%{$search}%")
                      ->orWhere('vehicle_no', 'like', "%{$search}%");
            });
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $alltransportvehicle->orderBy($sortBy, $sortOrder);
        } else {
            $alltransportvehicle->orderBy('created_at', 'asc');
        }

        $total = $alltransportvehicle->count();

        if ($limit <= 0) {
            $alltransportvehicleData = $alltransportvehicle->get();
        } else {
            $alltransportvehicleData = $alltransportvehicle->paginate($limit)->items();
        }

        return [
            'data' => $alltransportvehicleData,
            'total' => $total,
        ];
    }

    public function getTransportVehicle($id)
    {
        return $this->transportvehicles->find($id);
    }

    public function updateTransportVehicle($id, $data)
    {
        $transportvehicle = $this->transportvehicles->find($id);
        if ($transportvehicle) {
            $this->transportvehicles->updateTransportVehicle($id, $data);
            return "Updated Successfully.";
        }
    }

    public function deleteTransportVehicle($id)
    {
        $transportvehicle = $this->transportvehicles->find($id);
        if ($transportvehicle) {
            $transportvehicle->status = 0;
            $transportvehicle->save();
            return true;
        }
        return false;
    }
}
