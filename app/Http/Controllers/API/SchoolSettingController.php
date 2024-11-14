<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\SchoolSettingRepositoryInterface;

class SchoolSettingController extends Controller
{
    protected $SchoolSettingInterface;

    public function __construct(SchoolSettingRepositoryInterface $SchoolSettingInterface)
    {
        $this->SchoolSettingInterface = $SchoolSettingInterface;
    }
    public function GetSchoolSetting($id)
    {
        if (empty($id)) {
            return response()->json([
                'message' => 'No ID found',
            ], 422);
        }
        $school_setting = $this->SchoolSettingInterface->GetSchoolSetting($id);
        if (empty($school_setting['school_setting']) || $school_setting['branch_meta'] ) {
            throw new DataNotFoundException('No Data Found.');
        }
        return response()->json([
            'school_setting' => $school_setting['school_setting'],
            'branch_meta' => $school_setting['branch_meta'],
        ], 200);
    }
}
