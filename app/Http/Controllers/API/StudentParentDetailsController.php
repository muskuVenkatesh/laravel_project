<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\StudentParentDetailsInterface;

class StudentParentDetailsController extends Controller
{
    protected $studentparentdetailsinterface;

    public function __construct(StudentParentDetailsInterface $studentparentdetailsinterface)
    {
        $this->studentparentdetailsinterface = $studentparentdetailsinterface;
    }

    public function getStudentParentDetails(Request $request)
    {
        $StudentParentDetails = $this->studentparentdetailsinterface->getStudentParentDetails($request);
        
        if(empty($StudentParentDetails)) {
            return response()->json([
                'message' => "Data Not Found."
            ], 404);
        }

        return response()->json([
            'studentParentDetails' => $StudentParentDetails
        ], 200);
    }
}
