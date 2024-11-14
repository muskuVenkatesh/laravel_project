<?php

namespace App\Imports;

use App\Models\Branches;
use App\Models\Schools;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class BranchImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        set_time_limit(0);
        $branches = [];
        foreach ($rows as $row) {
            if (!isset($row['schoolcode']) || !isset($row['branchname']) || !isset($row['branchcode']) ||
                !isset($row['email']) || !isset($row['phone']) || !isset($row['address']) ||
                !isset($row['city']) || !isset($row['district']) || !isset($row['pincode']) ||
                !isset($row['state']) || !isset($row['status'])) {
                Log::warning('Missing required field in row: ', $row->toArray());
                continue;
            }

            $school = Schools::where('school_code', $row['schoolcode'])->first();
            if (!$school) {
                Log::warning('School not found for code: ' . $row['schoolcode']);
                continue;
            }

            $branches[] = [
                'school_id' => $school->id,
                'branch_name' => $row['branchname'],
                'branch_code' => $row['branchcode'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'address' => $row['address'],
                'city' => $row['city'],
                'dist' => $row['district'],
                'pin' => $row['pincode'],
                'state' => $row['state'],
                'status' => $row['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Log::info('Collected branches: ', $branches);
        if (count($branches) > 0) {
            $chunks = array_chunk($branches, 1000);
            foreach ($chunks as $chunk) {
                Branches::insert($chunk);
            }
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

    /**
     * Validation rules for the imported data.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.branchname' => 'required|string',
            '*.branchcode' => 'required|string',
            '*.email' => 'required|email',
            '*.phone' => 'required|string',
            '*.address' => 'required|string',
            '*.city' => 'required|string',
            '*.district' => 'required|string',
            '*.state' => 'required|string',
            '*.pincode' => 'required|integer',
            '*.status' => 'required'
        ];
    }
}
