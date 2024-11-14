<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\DateService;

class TransportVehicleDetails extends Model
{
    use HasFactory;
    protected $table = "transport_vehicles_details";
    protected $fillable = [
        'branch_id',
        'vehicle_type',
        'vehicle_no',
        'capacity',
        'insurance_expire',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function createTransportVehicle($data)
    {
       $dateformate = new DateService;
       return TransportVehicleDetails::create([
        'branch_id' => $data['branch_id'],
        'vehicle_type' => $data['vehicle_type'],
        'vehicle_no' => $data['vehicle_no'],
        'capacity' => $data['capacity'],
        'insurance_expire' => $dateformate->formatDate($data['insurance_expire'])
       ]);
    }

    public function updateTransportVehicle($id, $data)
    {
        $dateformate = new DateService;
        $transportvehicleDetails = TransportVehicleDetails::find($id);
        return $transportvehicleDetails->update([
         'vehicle_type' => $data['vehicle_type'] ?? $transportvehicleDetails->vehicle_type,
         'vehicle_no' => $data['vehicle_no'] ?? $transportvehicleDetails->vehicle_no,
         'capacity' => $data['capacity'] ?? $transportvehicleDetails->capacity,
         'insurance_expire' => $dateformate->formatDate($data['insurance_expire']) ?? $transportvehicleDetails->insurance_expire
        ]);
    }
}
