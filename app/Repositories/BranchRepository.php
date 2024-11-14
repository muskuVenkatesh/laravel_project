<?php

namespace App\Repositories;
use DB;
use App\Models\Schools;
use App\Models\Branches;
use App\Models\Branchmeta;
use Illuminate\Http\Request;
use App\Interfaces\BranchInterface;
use App\Models\SchoolBrancheSettings;
use App\Exceptions\DataNotFoundException;

class BranchRepository implements BranchInterface
{
    public function __construct(Schools $school,Branches $branch,Branchmeta $branchmeta, SchoolBrancheSettings $schoolbranchsetting)
    {
            $this->school = $school;
            $this->branch = $branch;
            $this->branchmeta = $branchmeta;
            $this->schoolbranchsetting = $schoolbranchsetting;
    }

    public function CreateBranch($data,$school_id){
        $branch = $this->branch->createBranch($data,$school_id);
        $branch_id = $branch->id;
        $branchmeta = $this->branchmeta->createBranchmeta($data,$branch_id);
        // $schoolbranchsetting = $this->schoolbranchsetting->createSetting($data, $school_id,$branch_id);
        return [
            'branch' => $branch,
            'branchmeta' => $branchmeta,
            // 'schoolbranchsetting'=>$schoolbranchsetting
        ];
    }

    public function GetBranchesByschoolId(Request $request, $limit, $school_id)
    {
        $total = Branches::where('status', 1)
                         ->where('school_id', $school_id)
                         ->withoutTrashed()
                         ->count();
        $branchDetails = Branches::where('branches.status', 1)
        ->where('branches.school_id', $school_id)
        ->leftJoin('academic_school_setups', 'academic_school_setups.branch_id', '=', 'branches.id')
        ->leftJoin('academic_details', 'academic_details.id', '=', 'academic_school_setups.academic_id')
        ->select(
            'branches.*',
            DB::raw("
                CASE
                    WHEN academic_details.start_date IS NOT NULL AND academic_details.end_date IS NOT NULL THEN
                        CONCAT(EXTRACT(YEAR FROM academic_details.start_date), '-', EXTRACT(YEAR FROM academic_details.end_date))
                    ELSE
                        NULL
                END as academic_year
            ")
        )->withoutTrashed();
        if ($request->has('q')) {
            $search = $request->input('q');
            if($search != null)
            {
               $branchDetails->where('branches.branch_name', 'like', "%{$search}%")
                ->orWhere('branches.branch_code', 'like', "%{$search}%");
            }

        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $branchDetails->orderBy($sortBy, $sortOrder);
        } else {
            $branchDetails->orderBy('branches.created_at', 'asc');
        }
        if ($limit <= 0) {
            $allbranchDetails = $branchDetails->get();
        } else {
            $allbranchDetails = $branchDetails->paginate($limit);
            $allbranchDetails = $allbranchDetails->items();
        }
        $total = $branchDetails->count();
        return ['data' => $allbranchDetails, 'total' => $total];
    }

    public function updateBranchById($data,$branchId){
        $branch = $this->branch->updateBranchById($data, $branchId);
        $branch_id = $branch->id;
        $branchmeta = $this->branchmeta->createBranchmeta($data,$branchId);
        // $schoolbranchsetting = $this->schoolbranchsetting->createBranchSetting($data,$branchId);
        return [
            'branch' => $branch,
            'branchmeta' => $branchmeta,
            // 'schoolbranchsetting' => $schoolbranchsetting
        ];
    }

    public function DeleteBranch($branch_id){
        $branch = Branches::find($branch_id);
        if (!$branch) {
            return response()->json([], 404);
        }
        $branch->delete();
        $branch->status = 0;
        $branch->save();
        return ['message' => 'Deleted Successfully.'];
    }

    public function restoreBranch($branch_id)
    {
        $branch = Branches::withTrashed()->find($branch_id);
        if (!$branch) {
            return [
                'message' => 'Branch not found',
                'statusUpdated' => false,
            ];
        }
        $branch->restore();
        $branch->status = '1';
        $branch->save();
        // Branchmeta::where('branch_id', $branch_id)->update(['status' => '1']);
        SchoolBrancheSettings::where('branch_id', $branch_id)->update(['status' => '1']);

        return [
            'message' => 'Branch and related records updated to active',
            'statusUpdated' => true,
        ];
    }

    public function ActiveBranches($school_id)
    {
        $school = Schools::find($school_id);
        if (!$school) {
            throw new DataNotFoundException('School Not Found');
        }
        $activeBranches = Branches::where('school_id', $school_id)
            ->where('status', '1')
            ->get();
        return $activeBranches;
    }

    public function GetSingleBranch($branch_id)
    {
        $branch = Branches::where('branches.id', $branch_id)
            ->leftJoin('academic_school_setups', 'academic_school_setups.school_id', '=', 'branches.school_id')
            ->leftJoin('academic_details', 'academic_details.id', '=', 'academic_school_setups.academic_id')
            ->select(
                'branches.*',
                DB::raw("
                    CASE
                        WHEN academic_details.start_date IS NOT NULL AND academic_details.end_date IS NOT NULL THEN
                            CONCAT(EXTRACT(YEAR FROM academic_details.start_date), '-', EXTRACT(YEAR FROM academic_details.end_date))
                        ELSE
                            NULL
                    END as academic_year
                ")
            )->first();

        $values = ['logo_file', 'report_card', 'text_logo', 'print_file'];
        $branchmeta = $this->branchmeta->branchData($branch_id, $values);
        $branch_meta_transformed = [];
        foreach ($branchmeta as $meta) {
            $branch_meta_transformed[$meta->name] = $meta->value;
        }

        $branch['branch_meta'] = $branch_meta_transformed;
        return [$branch];
    }
}
