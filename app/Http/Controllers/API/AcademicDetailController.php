<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\AcademicDetail;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Repositories\AcademicDetailsRepository;
use App\Http\Requests\StoreRequets\AcademicStoreRequests;
use App\Http\Requests\UpdateRequests\AcademicUpdateRequests;

class AcademicDetailController extends Controller
{
    protected $academicrepository;
    public function __construct(AcademicDetailsRepository $academicrepository,AcademicDetail $AcademicDetail){
        $this->academicrepository = $academicrepository;
        $this->AcademicDetail = $AcademicDetail;

    }

    public function  StoreAcademicDetails(AcademicStoreRequests $request)
    {
        $academic_exists = $this->AcademicDetail->where('academic_years',$request->input('academic_years'))->exists();
        if ($academic_exists) {
            return response()->json([
                'message' => 'Academic details for this academic year already exist.'
            ], 422);
        }
        $academicDetail = $this->academicrepository->store($request->validated());
        return response()->noContent();
    }

    public function GetAllAcademicDetails(Request $request)
    {
        $perPage = $request->input('_limit', 10);
        $academicdetails = $this->academicrepository->getAll($request, $perPage);
        if (empty($academicdetails['data']) || $academicdetails['total'] == 0){
            throw new DataNotFoundException('Data not found');
        }
        return response()->json([
            'data' => $academicdetails['data'],
            'total' => $academicdetails['total']
        ], 200);
    }

    public function GetAcademicDetailsById($id)
    {
        $academicDetail = $this->academicrepository->showAcademic($id);
        if (!$academicDetail){
            throw new DataNotFoundException('Data not found');
        }
        return response()->json(['academicDetails'=>$academicDetail], 200);
    }

    public function updateAcademicDetails(AcademicUpdateRequests $request, $id)
    {
        $academicDetail = $this->academicrepository->update($id, $request->validated());
        return response()->noContent();
    }

    public function DestroyAcademic($id)
    {
    $result = $this->academicrepository->destroy($id);
    if($result){
        return response()->json([
            'message' => 'Academic detail deleted successfully.',
        ], 200);
    }else{
        throw new DataNotFoundException('Failed to delete academic detail or data not found.');
    }
    }
}


