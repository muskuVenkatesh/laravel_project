<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportPilotSeeder extends Seeder
{
    public function run()
    {
        DB::table('transport_pilot_details')->insert([
            [
                'branch_id' => 1,
                'vehicle_id' => 1, 
                'route_id' => 1,   
                'name' => 'John Doe',
                'phone' => '1234567890',
                'alt_phone' => '0987654321',
                'license_type' => 'bus',
                'license_no' => 'LIC12345',
                'license_expire' => '2025-10-15',
                'life_insurance' => '1',
                'status' => '1', 
            ],
            [
                'branch_id' => 2,
                'vehicle_id' => 2, 
                'route_id' => 2,   
                'name' => 'Jane Smith',
                'phone' => '1122334455',
                'alt_phone' => '5544332211',
                'license_type' => 'mini-bus',
                'license_no' => 'LIC67890',
                'license_expire' => '2026-07-22',
                'life_insurance' => '0',
                'status' => '1',
            ],
            [
                'branch_id' => 3,
                'vehicle_id' => 3, 
                'route_id' => 3,   
                'name' => 'Alice Johnson',
                'phone' => '2233445566',
                'alt_phone' => '6677554433',
                'license_type' => 'car',
                'license_no' => 'LIC34567',
                'license_expire' => '2024-04-30',
                'life_insurance' => '1',
                'status' => '1', 
            ],
        ]);
    }
}
