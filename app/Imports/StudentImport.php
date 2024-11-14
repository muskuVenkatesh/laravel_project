<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Parents; // Ensure this matches your actual model name
use App\Models\Students;
use App\Models\Classes; // Ensure this matches your actual model name
use App\Models\Section;
use App\Models\UserDetails;
use App\Models\AcademicDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
class StudentImport implements ToCollection, WithHeadingRow
{
    protected $branchId;

    public function __construct($branchId)
    {
        $this->branchId = $branchId;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        set_time_limit(0);

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                // Validate required fields
                if (empty($row['parent_first_name']) || empty($row['email']) || empty($row['student_first_name'])) {
                    Log::warning('Skipping row due to missing required fields: ' . json_encode($row));
                    continue;
                }

                // Insert into users table
                $userId = DB::table('users')->insertGetId([
                    'name' => $row['parent_first_name'],
                    'email' => $row['email'],
                    'roleid' => 6,
                    'phone' => $row['phone'] ?? null,
                    'status' => 1,
                    'password' => Hash::make('password'),
                ]);// die;

                // Insert into parents table
                $parentId = DB::table('parents')->insertGetId([
                    'branch_id' => $this->branchId,
                    'user_id' => $userId,
                    'first_name' => $row['parent_first_name'],
                    'last_name' => $row['last_name'],
                    'phone' => $row['phone'],
                    'alt_email' => $row['email']
                ]);

                // Insert into user_details table
                DB::table('user_details')->insert([
                    'user_id' => $userId,
                    'branch_id' => $this->branchId,
                    'gender' => $row['parent_gender'] ?? null,
                    'aadhaar_card_no' => $row['parent_aadhaar_card_no'] ?? null,
                    'pan_card_no' => $row['parent_pan_card_no'] ?? null,
                ]);

                // Fetch class and section IDs
                $classId = DB::table('classes')
                    ->where('name', $row['class'])
                    ->where('branch_id', $this->branchId)
                    ->value('id');

                $sectionId = DB::table('sections')
                    ->where('name', $row['section'])
                    ->where('class_id', $classId)
                    ->value('id');

                $academicYearId = DB::table('academic_details')
                    ->where('academic_years', $row['academic_year'])
                    ->value('id');

                // Check if class, section, and academic year exist
                if (!$classId || !$sectionId || !$academicYearId) {
                    Log::warning('Class, section, or academic year not found: ' . json_encode($row));
                    continue;
                }

                // Insert into students table
                $studentId = DB::table('students')->insertGetId([
                    'branch_id' => $this->branchId,
                    'academic_year_id' => $academicYearId,
                    'parent_id' => $parentId,
                    'roll_no' => $row['roll_no'] ?? null,
                    'first_name' => $row['student_first_name'],
                    'last_name' => $row['student_last_name'],
                    'medium_id' => 1,
                    'class_id' => $classId,
                    'section_id' => $sectionId,
                    'admission_no' => $row['admission_no'] ?? null,
                    'application_no' => $row['application_no'] ?? null,
                    'admission_date' => Carbon::createFromFormat('d-m-Y', $row['date_of_birth'])->format('Y-m-d'),
                ]);

                // Insert into user_details table for student
                DB::table('user_details')->insert([
                    'user_id' => $studentId,
                    'branch_id' => $this->branchId,
                    'date_of_birth' => isset($row['date_of_birth']) ? Carbon::createFromFormat('d-m-Y', $row['date_of_birth'])->format('Y-m-d') : null,
                    'gender' => $row['gender'] ?? null,
                    'blood_group' => $row['blood_group'] ?? null,
                    'religion' => $row['religion'] ?? null,
                    'cast' => $row['cast'] ?? null,
                    'aadhaar_card_no' => $row['student_aadhaar_card_no'] ?? null,
                    'pan_card_no' => $row['student_pan_card_no'] ?? null,
                    'address' => $row['permanent_address'] ?? null,
                    'tmp_address' => $row['present_address'] ?? null,
                ]);
            }

            DB::commit();
            Log::info('Successfully imported student, parent, and user data.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error importing data: ' . $e->getMessage());
        }
    }
}
