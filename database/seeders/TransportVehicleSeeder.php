<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportVehicleSeeder extends Seeder
{
    public function run()
    {
        DB::table('transport_vehicles_details')->insert([
            [
                'branch_id' => 1,
                'vehicle_type' => 'bus',
                'vehicle_no' => 'BUS1234',
                'capacity' => 50,
                'insurance_expire' => '2024-12-31',
                
            ],
            [
                'branch_id' => 2,
                'vehicle_type' => 'mini-bus',
                'vehicle_no' => 'MB5678',
                'capacity' => 30,
                'insurance_expire' => '2025-06-30',
                
            ],
            [
                'branch_id' => 3,
                'vehicle_type' => 'car',
                'vehicle_no' => 'CAR9101',
                'capacity' => 5,
                'insurance_expire' => '2024-09-15',
                
            ],
        ]);
    }
}
