<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\MetaSeeder;
use Database\Seeders\AcademicSchoolSeeder;
use App\Models\Schools;
use App\Models\Branches;
use App\Models\AcademicSchoolSetup;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = Schools::all();

        $sids = $schools->map(function ($item) {
            return [
                    'school_id' => $item->id
                ];
        })->toArray();

        $branchData = Branches::factory()->count(count($sids))->make()->toArray(); // Ensure the count matches

        // Merge branch data with school IDs
        $schoolsData = array_map(function ($branch, $id) {
            return array_merge($branch, $id);
        }, $branchData, $sids);

        // Insert all records in a single query
       $branch = Branches::insert($schoolsData);
       $branch_data = Branches::all();
       $data['academic_id'] = 1;

       foreach($branch_data as $branchdata)
       {
            AcademicSchoolSetup::createSetup($data, $branchdata->id, $branchdata->school_id);
       }
    }
}
