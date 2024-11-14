<?php

namespace App\Repositories;

use App\Models\Schools;
use App\Models\Branches;
use App\Models\CurrencyTypes;
use App\Models\SchoolBrancheSettings;
use App\Models\Branchmeta;
use App\Interfaces\SchoolInterface;
use Illuminate\Http\Request;
use App\Interfaces\SchoolSettingRepositoryInterface;
use Pagination;
use DB;

class SchoolRepository implements SchoolInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(Schools $school ,Branches $branch, SchoolBrancheSettings $schoolbranchsetting,Branchmeta $branchmeta, SchoolSettingRepositoryInterface $schoolSetting)
    {
           $this->school = $school;
           $this->branch = $branch;
           $this->schoolbranchsetting = $schoolbranchsetting;
           $this->branchmeta = $branchmeta;
           $this->schoolSetting = $schoolSetting;
    }

    public function CreateSchool($data){
        $school = $this->school->createSchool($data);
        $branch = $this->branch->createBranch($data, $school);
        $branch_id = $branch->id;
        $branchmeta = $this->branchmeta->createBranchmeta($data,$branch_id);
        $schoolbranchsetting = $this->schoolbranchsetting->createSetting($data, $school,$branch_id);
        return $branch;
    }

    public function GetSchool($data)
    {
        $school_data = $this->school
        ->where('schools.id', $data)
        ->join('academic_school_setups', 'academic_school_setups.school_id', '=', 'schools.id')
        ->join('academic_details', 'academic_details.id', '=', 'academic_school_setups.academic_id')
        ->select(
            'schools.*',
            'academic_school_setups.academic_id',
            DB::raw("
                    CASE
                        WHEN academic_details.start_date IS NOT NULL AND academic_details.end_date IS NOT NULL THEN
                            CONCAT(EXTRACT(YEAR FROM academic_details.start_date), '-', EXTRACT(YEAR FROM academic_details.end_date))
                        ELSE
                            NULL
                    END as academic_year
                ")
        )->first();

        $branches = $this->branch->where('school_id', $data)->get();

        $school_settings = $this->schoolSetting->GetSchoolSetting($data);

        $branch_ids = $branches->pluck('id');
        $fileds = ['logo_file', 'report_card', 'text_logo', 'print_file'];
        $branch_meta = $this->branchmeta->whereIn('branch_id', $branch_ids)->whereIn('name', $fileds)->get();

        $branch_meta_transformed = [];
        foreach ($branch_meta as $meta) {
            if (!isset($branch_meta_transformed[$meta->branch_id])) {
                $branch_meta_transformed[$meta->branch_id] = [];
            }
            $branch_meta_transformed[$meta->branch_id][$meta->name] = $meta->value;
        }

        $school_data['schools_settings'] = $school_settings['school_setting'];
        $school_data['branches'] = $branches;

        foreach ($branches as $branch) {
            $branch_id = $branch->id;
            if (isset($branch_meta_transformed[$branch_id])) {
                $branch['branch_meta'] = $branch_meta_transformed[$branch_id];
            } else {
                $branch['branch_meta'] = [];
            }
        }
        return $school_data;
    }

    public function UpdateSchool($schoolId, $data)
    {
        $school = $this->school->updateSchool($schoolId,$data);
        $branch = $this->branch->updateBranch($data, $schoolId);
        $branch_id = $branch->id;
        $branchmeta = $this->branchmeta->createBranchmeta($data,$branch_id);
        $schoolbranchsetting = $this->schoolbranchsetting->updateSetting($data, $schoolId,$branch_id);
        return $school;
    }


    public function GetAllSchool(Request $request, $limit)
    {
        $total = Schools::where('schools.status', 1)->withoutTrashed()->count();

        $allschool = Schools::query()
            ->where('schools.status', 1)
            ->withoutTrashed()
            ->leftJoin('academic_school_setups', 'academic_school_setups.school_id', '=', 'schools.id')
            ->leftJoin('academic_details', 'academic_details.id', '=', 'academic_school_setups.academic_id')
            ->select(
                'schools.*',
                DB::raw("
                    CASE
                        WHEN academic_details.start_date IS NOT NULL AND academic_details.end_date IS NOT NULL THEN
                            CONCAT(EXTRACT(YEAR FROM academic_details.start_date), '-', EXTRACT(YEAR FROM academic_details.end_date))
                        ELSE
                            NULL
                    END as academic_year
                ")
            );

        if ($request->has('q')) {
            $search = $request->input('q');
            if($search != null)
            {
                $allschool->where(function ($query) use ($search) {
                    $query->where('schools.name', 'like', "%{$search}%")
                        ->orWhere('schools.school_code', 'like', "%{$search}%")
                        ->orWhere('schools.address', 'like', "%{$search}%")
                        ->orWhere('schools.city', 'like', "%{$search}%")
                        ->orWhere('schools.dist', 'like', "%{$search}%")
                        ->orWhere('schools.state', 'like', "%{$search}%")
                        ->orWhere('schools.pin', 'like', "%{$search}%")
                        ->orWhere('schools.status', 'like', "%{$search}%");
                });
            }

        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allschool->orderBy($sortBy, $sortOrder);
        } else {
            $allschool->orderBy('schools.created_at', 'asc');
        }

        if ($limit <= 0) {
            $allschoolData = $allschool->get();
        } else {
            $allschoolData = $allschool->paginate($limit);
            $allschoolData = $allschoolData->items();
        }

        return ['data' => $allschoolData, 'total' => $total];
    }

    public function DeleteSchool($data)
    {
        $school = Schools::with('branches')->find($data);
        return $school;
    }

    public function GetCurrencyType()
    {
        $currency = CurrencyTypes::where('status', 1)->get(['id', 'name', 'symbol']);
        return $currency;
    }
}

