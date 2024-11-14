<?php

namespace App\Http\Controllers\API;

use App\Models\Student;
use App\Jobs\StudentProcess;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\StudentInterface;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;

class StudentController extends Controller
{

    protected $studentrepo;

    public function __construct(StudentInterface $studentrepo)
    {
        $this->studentrepo = $studentrepo;
    }
    public function CreateStudent(StoreStudentRequest $request)
    {

        $data = $request->validated();
        $student = $this->studentrepo->store($data);
        return response()->json([
            'data' => "Student Create Successfully."
        ], 201);
    }

    public function GetStudent($id)
    {
        $student = $this->studentrepo->getStudent($id);
        if ($student) {
            return response()->json(['student' => $student], 200);
        } else {
            $data = 'id is Invalied';
        }
    }

    public function GetStudents(Request $request)
    {
        $branchId = $request->input('branch_id');
        $classId = $request->input('class_id');
        $section_id = $request->input('section_id');
        $search = $request->input('q');
        $sortBy = $request->input('sort_by', 'first_name');
        $sortOrder = $request->input('sort_order', 'asc');
        $perPage = $request->input('per_page', 10);

        $student = $this->studentrepo->GetStudents($branchId, $classId, $section_id, $search, $sortBy, $sortOrder, $perPage);
        if (count($student) === 0) {
            throw new DataNotFoundException('No Student Found');
        } else {
            return response()->json([
                'student' => $student
            ], 200);
        }
    }

    public function getStudentByBranch(Request $request)
    {
        $branch_id = $request->input('branch_id');
        $student = $this->studentrepo->getStudentByBranch($branch_id);
        if (!empty($student) && count($student)>0 ){
            return response()->json([
                'data' => $student
            ],200);
        }
        throw new DataNotFoundException('No Student Found for the Branch');
    }

    public function getStudentByClass(Request $request)
    {
        $class_id = $request->input('class_id');
        $branch_id = $request->input('branch_id');
        $section_id = $request->input('section_id');
        $student = $this->studentrepo->getStudentByClass($class_id, $branch_id, $section_id);

        if ($student && count($student) > 0) {
            return response()->json([
                'data' => $student
            ],200);
        }
        throw new DataNotFoundException('No Student Found for the Class');
    }

    public function UpdateStudent($id, UpdateStudentRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->studentrepo->updateStudent($id, $validatedData,);
        return response()->noContent();
    }

    public function DeleStudent($id)
    {
        $student = $this->studentrepo->DeleStudent($id);
        return response()->json(['message'=>'Student Deleted Successfully'],200);
    }

    public function UploadFile(Request $request)
    {
        if ($request->hasFile('file')) {

            $branch_id = $request->input('branch_id');
            $file = $request->file('file');
            $fileContent = file_get_contents($file->getRealPath());
            $base64File = base64_encode($fileContent);
            StudentProcess::dispatch($base64File, $file->getClientOriginalName(), $branch_id);
            return response()->json(['message' => 'Data import has been queued']);
        }
        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function GetInactiveStudents(Request $request)
    {
        $classId = $request->input('class_id');
        $search = $request->input('q');
        $sortBy = $request->input('sort_by', 'first_name');
        $sortOrder = $request->input('sort_order', 'asc');
        $perPage = $request->input('per_page', 15);

        $students = $this->studentrepo->GetInactiveStudents($classId, $search, $sortBy, $sortOrder, $perPage);
        if ($students && count($students) > 0 ) {
            return response()->json([
                'students' => $students
            ], 200);
        } else {
            throw new DataNotFoundException('No Student Found In Inactive State');
        }
    }

    public function getGapDetails(Request $request)
    {
        $students = $this->studentrepo->getGapDetails($request);
        return response()->json([
            'students' => $students,
            'message' => 'Parent not found for the Student'
        ], 200);
    }
}
