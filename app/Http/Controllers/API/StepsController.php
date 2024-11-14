<?php

namespace App\Http\Controllers\API;

use App\Models\Branchmeta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;

class StepsController extends Controller
{
    public function GetSteps($branch_id)
    {
        $fileFields = ['school_create',
        'branch_create', 'branch_staff', 'class_create',
        'section_create', 'subject_create', 'student_create',
        'parent_create'];
        $branch_meta = Branchmeta::branchData($branch_id, $fileFields);
        $branch_meta_data = [];
        foreach ($branch_meta as $meta) {
            $branch_meta_data[$meta->name] = $meta->value;
        }
        if(empty($branch_meta_data))
        {
            throw new DataNotFoundException('No branch meta data found');
        }
        return response()->json
        ([
            'data' => $branch_meta_data
        ],200);
    }
}
