<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\PilotDetails;
use App\Interfaces\PilotInterface;

class PilotRepository implements PilotInterface
{
    protected $pilotdetails;

    public function __construct(PilotDetails $pilotdetails)
    {
        $this->pilotdetails = $pilotdetails;
    }

    public function createpilot($data)
    {
        $createdtransportvehicles = $this->pilotdetails->createTransportPilot($data);
        return $createdtransportvehicles;
    }

    public function getAllPilot(Request $request)
    {
        $limit = $request->input('_limit', 10);
        $branch_id = $request->input('branch_id');
        $allpilot = PilotDetails::query()
            ->where('branch_id', $branch_id)
            ->where('status', 1);

        if ($request->has('q')) {
            $search = $request->input('q');
            $allpilot->where(function ($query) use ($search) {
                $query->where('license_no', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('license_type', 'like', "%{$search}%");
            });
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allpilot->orderBy($sortBy, $sortOrder);
        } else {
            $allpilot->orderBy('created_at', 'asc');
        }

        $total = $allpilot->count();

        if ($limit <= 0) {
            $allpilotData = $allpilot->get();
        } else {
            $allpilotData = $allpilot->paginate($limit)->items();
        }
        return [
            'data' => $allpilotData,
            'total' => $total,
        ];
    }

    public function getAllPilotbyid($id)
    {
        return $this->pilotdetails->find($id);
    }

    public function updatePilot($id, $data)
    {
        $pilot = $this->pilotdetails->find($id);
        if ($pilot) {
            $this->pilotdetails->updatePilot($id, $data);
            return "Updated Successfully.";
        }
    }

    public function deletepilot($id)
    {
        $pilot = $this->pilotdetails->find($id);
        if ($pilot) {
            $pilot->status = 0;
            $pilot->save();
            return true;
        }
        return false;
    }

}
