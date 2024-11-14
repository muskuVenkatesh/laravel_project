<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolBrancheSettings extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table ='school_branche_settings';
    protected $fillable = [
        'school_id',
        'branch_id',
        'stud_grade',
        'reg_start_from',
        'reg_prefix_digit',
        'offline_payments',
        'fees_due_days',
        'cal_fees_fine',

    ];

    public function branchMeta()
    {
        return $this->hasOne(Branchmeta::class, 'branch_id', 'branch_id');
    }


    public  function createSetting($data, $school_id, $branch_id)
{
    $branchSetting = SchoolBrancheSettings::updateOrCreate(
        [
            'school_id' => $school_id,
            'branch_id' => $branch_id,
        ],
        [
            'stud_grade' => $data['stud_grade'] ?? null,
            'reg_start_from' => $data['reg_start_from'] ?? null,
            'reg_prefix_digit' => $data['reg_prefix_digit'] ?? null,
            'offline_payments' => $data['offline_payments'] ?? null,
            'fees_due_days' => $data['fees_due_days'] ?? null,
            'cal_fees_fine' => $data['cal_fees_fine'] ?? null,
        ]
    );

    return $branchSetting;
}


    public function updateSetting($data, $schoolId, $branchId)
    {
        $setting = SchoolBrancheSettings::where('school_id', $schoolId)
                                        ->where('branch_id', $branchId)
                                        ->first();

        if ($setting) {
            $setting->update([
                'stud_grade' => $data['stud_grade'],
                'reg_start_from' => $data['reg_start_from'],
                'reg_prefix_digit' => $data['reg_prefix_digit'],
                'offline_payments' => $data['offline_payments'],
                'fees_due_days' => $data['fees_due_days'],
                'cal_fees_fine' => $data['cal_fees_fine'],
            ]);

            return $setting;
        }
        return null;
    }

    public function schoolData($id)
    {
        $school_data = SchoolBrancheSettings::where('school_id', $id)->first();
        return $school_data;
    }

    public static function getPrifiex($branch_id)
    {
        return SchoolBrancheSettings::where('branch_id', $branch_id)->value('reg_prefix_digit');
    }
}
