<?php

namespace App\Repositories;
use App\Interfaces\SchoolSettingRepositoryInterface;
use App\Models\SchoolBrancheSettings;
use App\Models\Branchmeta;

class SchoolSettingRepository implements SchoolSettingRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(SchoolBrancheSettings $schoolbrancheSettings, Branchmeta $branchmeta)
    {
           $this->schoolbrancheSettings = $schoolbrancheSettings;
           $this->branchmeta = $branchmeta;
    }

    public function GetSchoolSetting($id)
    {
        $school_setting = $this->schoolbrancheSettings->schoolData($id);
        $branch_meta_transformed = [];
        if ($school_setting && $school_setting->branch_id !== null) {
            $fileds = ['logo_file', 'report_card', 'text_logo', 'print_file'];
            $branchmeta = $this->branchmeta->branchData($school_setting->branch_id, $fileds);
            foreach ($branchmeta as $meta) {
                $branch_meta_transformed[$meta->name] = $meta->value;
            }
        }
        return [
            'school_setting' => $school_setting,
            'branch_meta' => $branch_meta_transformed,
        ];
    }
}
