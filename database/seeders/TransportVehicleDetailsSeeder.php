<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransportVehicleDetails;

class TransportVehicleDetailsSeeder extends Seeder
{
    public function run(): void
    {
        TransportVehicleDetails::insert([
            [
                'branch_id' => 1,
                'vehicle_type' => 'bus',
                'vehicle_no' => 'BUS001',
                'capacity' => 50,
                'insurance_expire' => '2025-06-30',
            ],
            [
                'branch_id' => 1,
                'vehicle_type' => 'mini-bus',
                'vehicle_no' => 'MINI002',
                'capacity' => 30,
                'insurance_expire' => '2025-08-15',
            ],
            [
                'branch_id' => 2,
                'vehicle_type' => 'car',
                'vehicle_no' => 'CAR003',
                'capacity' => 5,
                'insurance_expire' => '2024-12-01',
            ],
        ]);
    }
}
