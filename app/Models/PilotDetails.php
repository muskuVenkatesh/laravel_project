<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\DateService;

class PilotDetails extends Model
{
    use HasFactory;

    protected $table = 'transport_pilot_details';

    protected $fillable = [
        'branch_id',
        'vehicle_id',  
        'route_id',
        'name',
        'phone',
        'alt_phone',
        'license_type',
        'license_no',
        'license_expire',
        'life_insurance',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function createTransportPilot($data)
    {
        $dateFormatService = new DateService;
        return PilotDetails::create([
            'branch_id' => $data['branch_id'],
            'vehicle_id' => $data['vehicle_id'], 
            'route_id' => $data['route_id'],     
            'name' => $data['name'],
            'phone' => $data['phone'],
            'alt_phone' => $data['alt_phone'],
            'license_type' => $data['license_type'],
            'license_no' => $data['license_no'],
            'license_expire' => $dateFormatService->formatDate($data['license_expire']),
            'life_insurance' => $data['life_insurance'],
        ]);
    }
    
    public function updatePilot($id, $data)
    {
        $dateFormatService = new DateService;
        $pilotDetails = PilotDetails::find($id);
        return $pilotDetails->update([
            'vehicle_id' => $data['vehicle_id'], 
            'route_id' => $data['route_id'],       
            'name' => $data['name'],
            'phone' => $data['phone'],
            'alt_phone' => $data['alt_phone'],
            'license_type' => $data['license_type'],
            'license_no' => $data['license_no'],
            'license_expire' => $dateFormatService->formatDate($data['license_expire']) ?? $pilotDetails->license_expire,
            'life_insurance' => $data['life_insurance'],
        ]);
    }

}

