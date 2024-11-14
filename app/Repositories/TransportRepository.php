<?php

namespace App\Repositories;

use App\Models\TransportDetails;
use Illuminate\Http\Request;
use App\Interfaces\TransportInterface;
use DB;

class TransportRepository implements TransportInterface
{
    protected $transportdetails;

    public function __construct(TransportDetails $transportdetails)
    {
        $this->transportdetails = $transportdetails;
    }

    public function CreateTransport($data)
    {
        $transport_id = $this->transportdetails->CreateTransport($data);
        if($transport_id)
        {
            return (['message' => "Create Successfully", 'code' => 200]);
        }
        return (['message' => "In this route no more pailots", 'code' => 404]);
    }

    public function GetAllTransportDetails(Request $request)
    {
        $limit = $request->input('_limit');
        $branch_id = $request->input('branch_id');
        $alltransportdetails = TransportDetails::where('transport_details.status', 1)
            ->where('transport_details.branch_id', $branch_id)
            ->join('transport_vehicles_details', 'transport_vehicles_details.id', '=', 'transport_details.vehicle_id')
            ->join('transport_pilot_details', 'transport_pilot_details.id', '=', 'transport_details.pilot_id')
            ->join('students', 'students.id', '=', 'transport_details.student_id')
            ->join('transport_routes', 'transport_routes.id', '=', DB::raw("CAST(transport_details.transport->>'route_id' AS bigint)"))
            ->select(
                'transport_details.id',
                'transport_details.branch_id',
                'transport_details.student_id',
                'transport_details.vehicle_id',
                'transport_details.pilot_id',
                'transport_details.status',
                'transport_details.created_at',
                'transport_details.updated_at',
                TransportDetails::raw("CONCAT(students.first_name, ' ', students.last_name) as student_name"),
                'transport_pilot_details.name as pilot_name',
                'transport_routes.start_point',
                'transport_routes.end_point',
                'transport_vehicles_details.vehicle_type',
                DB::raw("transport_details.transport->>'pickup_point' as pickup_point"),
                DB::raw("CASE
                            WHEN CAST(transport_details.transport->>'is_same_drop' AS integer) = 1
                            THEN transport_details.transport->'extra_drop_details'->>'drop_point'
                            ELSE transport_details.transport->>'drop_point'
                        END as drop_point")
            );

        $total = $alltransportdetails->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $alltransportdetails->where('transport_details.id', 'like', "%{$search}%");
        }
        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $alltransportdetails->orderBy($sortBy, $sortOrder);
        } else {
            $alltransportdetails->orderBy('created_at', 'asc');
        }
        if ($limit <= 0) {
            $alltransportdetailsData = $alltransportdetails->get();
        } else {
            $alltransportdetailsData = $alltransportdetails->paginate($limit);
            $alltransportdetailsData = $alltransportdetailsData->items();
        }
        return ['data' => $alltransportdetailsData, 'total' => $total];
    }


    public function GetTransportDetailsById($id)
    {
        return $this->transportdetails->find($id);
    }

    public function UpdateTransportDetails($id, $data)
    {
        $transportdetails = $this->transportdetails->find($id);
        if ($transportdetails) {
            $transportdetails->update($data);
            return "Updated Successfully.";
        }
    }

    public function DeleteTransportDetails($id)
    {
        $DeletedTransportDetails = $this->transportdetails->find($id);
        if ($DeletedTransportDetails) {
            $DeletedTransportDetails->status = 0;
            $DeletedTransportDetails->save();
            return true;
        }
        return false;
    }

    public function getTransportDetailsByStudentId($student_id)
    {
        $transportDetails = TransportDetails::with(['student.user', 'pilot', 'route'])
        ->where('student_id', $student_id)
            ->whereNull('deleted_at')
            ->first();
        if ($transportDetails) {
            return [
                'id'           => $transportDetails->id,
                'route_id'     => $transportDetails->route->id,
                'student_name' => $transportDetails->student->user->name,
                'route_name'   => $transportDetails->route->start_point . ' - ' . $transportDetails->route->end_point,
                'pilot_name'   => $transportDetails->pilot->name,
                'pilot_phone'   => $transportDetails->pilot->phone,
                'stoppage_name' => $transportDetails->stop_name,
            ];
        }
        return null;
    }

}
