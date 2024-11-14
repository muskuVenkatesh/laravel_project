<?php

namespace App\Imports;

use App\Models\Schools;
use App\Models\Branches;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class  SchoolImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        set_time_limit(0);
        // print_r($rows);die;
        $schools = [];
        $branches = [];

        foreach ($rows as $row) {

            if (!isset($row['name']) || !isset($row['school_code']) || !isset($row['address'])
                || !isset($row['city']) || !isset($row['district']) || !isset($row['state']) || !isset($row['pin_code'])
                || !isset($row['status']) || !isset($row['branch_name']) || !isset($row['branch_code'])
                || !isset($row['email']) || !isset($row['phone']) || !isset($row['branch_address'])
                || !isset($row['branch_city']) || !isset($row['branch_district']) || !isset($row['branch_pincode'])) {
                continue;
            }

            $schools[] = [
                'name' => $row['name'],
                'school_code' => $row['school_code'],
                'address' => $row['address'],
                'city' => $row['city'],
                'dist' => $row['district'],
                'state' => $row['state'],
                'pin' => $row['pin_code'],
                'status'=> $row['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $branches[] = [
                'school_code' => $row['school_code'],
                'branch_name' => $row['branch_name'],
                'branch_code' => $row['branch_code'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'address' => $row['branch_address'],
                'city' => $row['branch_city'],
                'dist' => $row['branch_district'],
                'pin' => $row['branch_pincode'],
                'state' => $row['state'],
                'status' => $row['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Log::info('Collected branches: ', $branches);
        Log::info('Collected schools: ', $schools);

        $schoolChunks = array_chunk($schools, 1000);
        foreach ($schoolChunks as $chunk) {
            Schools::insert($chunk);
        }

        $schoolModels = Schools::whereIn('school_code', array_column($schools, 'school_code'))->get();
        $school_ids = $schoolModels->keyBy('school_code')->map->id;

        foreach ($branches as &$branch) {
            if (isset($school_ids[$branch['school_code']])) {
                $branch['school_id'] = $school_ids[$branch['school_code']];
                unset($branch['school_code']);
            } else {
                \Log::error('School not found for branch', ['branch' => $branch]);
                continue;
            }
        }

        $branchChunks = array_chunk($branches, 1000);
        foreach ($branchChunks as $chunk) {
            Branches::insert($chunk);
        }
    }
    /**
     * Define the chunk size.
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
