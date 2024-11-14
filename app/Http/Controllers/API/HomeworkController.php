<?php

namespace App\Http\Controllers\API;

use App\Models\Homework;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\HomeworkInterface;
use App\Http\Requests\HomeWorkRequest;
use App\Exceptions\DataNotFoundException;

class HomeworkController extends Controller
{
    protected $homeworkInterface;

    public function __construct(HomeworkInterface $homeworkInterface)
    {
        $this->homeworkInterface = $homeworkInterface;
    }

    public function storeHomework(HomeWorkRequest $request)
    {
            $validatedData = $request->validated();
            $data = $this->homeworkInterface->storeHomework($validatedData);
            return response()->json([
                'data' => $data
            ]);
    }

    public function getHomeworks(Request $request)
    {
        $validatedData = $request->validate([
            'branch_id' => 'required|integer',
            'class_id' => 'required|integer',
            'section_id' => 'required',
        ]);
        $branchId = $validatedData['branch_id'];
        $classId = $validatedData['class_id'];
        $sectionId = $validatedData['section_id'];
        $homeworkDate = $request->input('date');
        $date = ($homeworkDate) ? $this->getFormattedDate($homeworkDate) : $this->getToday();

        $data = $this->homeworkInterface->getHomeworks($branchId, $classId, $sectionId, $date);
        if(empty($data)){
            throw new DataNotFoundException('Reacords Not Found');
        }
            return response()->json([
                'data' => $data
            ]);
    }

    public function getHomework($id)
    {
        $data = $this->homeworkInterface->getHomework($id);
        return response()->json([
            'data' => $data
        ]);
    }
}
