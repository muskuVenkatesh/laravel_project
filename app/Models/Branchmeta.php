<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branchmeta extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table ='branch_meta';
    protected $fillable = [
        'branch_id',
        'name',
        'value',
    ];

    public function schoolBrancheSettings()
    {
        return $this->belongsTo(SchoolBrancheSettings::class, 'branch_id', 'branch_id');
    }

    public static function branchData($branch_id, $where)
    {
        $branch_data = Branchmeta::where('branch_id', $branch_id)->whereIn('name', $where)->get();
        return $branch_data;
    }

    public static function createBranchmeta($data, $branch_id)
{
    $fileFields = [
            'logo_file', 'report_card', 'text_logo', 'print_file', 'personality_traits',
            'school_create_email', 'branch_create_email', 'school_create',
            'branch_create', 'branch_staff', 'class_create',
            'section_create', 'subject_create', 'student_create',
            'parent_create', 'subject_select', 'period_attendance', 'id_card_template',
            'receipt_template', 'report_card_template'
    ];

    $metaResponses = [];
    $afterPrintFile = false;

        $period_attendance = array_key_exists('period_attendance', $data) ? $data['period_attendance'] : 0;

    foreach ($fileFields as $field) {
        if ($field === 'print_file') {
            $afterPrintFile = true;
        }

        if (in_array($field, ['branch_create', 'school_create'])) {
            $branchMeta = Branchmeta::where('branch_id', $branch_id)
                ->where('name', $field)
                ->first();

            if ($branchMeta) {
                $branchMeta->update(['value' => '1']);
            } else {
                $branchMeta = Branchmeta::create([
                    'branch_id' => $branch_id,
                    'name' => $field,
                    'value' => '1',
                ]);
            }

            $metaResponses[$field] = $branchMeta;
            continue;
        }

        if (isset($data[$field]) && is_file($data[$field])) {
            $filePath = $data[$field]->store($field, 'public');

            $branchMeta = Branchmeta::where('branch_id', $branch_id)
                ->where('name', $field)
                ->first();

            if ($branchMeta) {
                $branchMeta->update(['value' => $filePath]);
            } else {
                $branchMeta = Branchmeta::create([
                    'branch_id' => $branch_id,
                    'name' => $field,
                    'value' => $filePath,
                ]);
            }

            $metaResponses[$field] = $branchMeta;
        } elseif ($afterPrintFile) {
            $branchMeta = Branchmeta::where('branch_id', $branch_id)
                                    ->where('name', $field)
                                    ->first();

            if ($branchMeta) {
                $branchMeta->update(['value' => '0']);
            } else {
                $branchMeta = Branchmeta::create([
                    'branch_id' => $branch_id,
                    'name' => $field,
                    'value' => '0',
                ]);
            }

            $metaResponses[$field] = $branchMeta;
        }

            // Handle personality_traits (expecting values 0 or 1)
        if ($field === 'personality_traits' && isset($data[$field])) {
            $personalityTraitsValue = $data[$field];

            $branchMeta = Branchmeta::where('branch_id', $branch_id)
                ->where('name', 'personality_traits')
                ->first();

            if ($branchMeta) {
                $branchMeta->update(['value' => $personalityTraitsValue]);
            } else {
                $branchMeta = Branchmeta::create([
                    'branch_id' => $branch_id,
                    'name' => 'personality_traits',
                    'value' => $personalityTraitsValue,
                ]);
            }

            $metaResponses['personality_traits'] = $branchMeta;
        }
        if ($field === 'id_card_template' && isset($data[$field])) {
            Branchmeta::updateBranchMeta('id_card_template', $data, $branch_id, $metaResponses);
        }

        if ($field === 'receipt_template' && isset($data[$field])) {
            Branchmeta::updateBranchMeta('receipt_template', $data, $branch_id, $metaResponses);
        }

        if ($field === 'report_card_template' && isset($data[$field])) {
            Branchmeta::updateBranchMeta('report_card_template', $data, $branch_id, $metaResponses);
            $student_fields = ['student_photo', 'principale_sing'];
            if ($data['report_card_template'] == 1) {
                Branchmeta::updatestudentfields($student_fields, $branch_id, $data);
            }
        }
    }

        if (array_key_exists('period_attendance', $data) && $data['period_attendance'] != 0) {
            $branchdata = Branchmeta::where('branch_id', $branch_id)
                                    ->where('name', 'period_attendance')
                                    ->first();
            if ($branchdata) {
                $branchdata->update(['value' => $data['period_attendance']]);
            }
        }

        if (array_key_exists('subject_select', $data) && $data['subject_select'] != 0) {
            $branchdata = Branchmeta::where('branch_id', $branch_id)
                                    ->where('name', 'subject_select')
                                    ->first();
            if ($branchdata) {
                $branchdata->update(['value' => $data['subject_select']]);
            }
        }

    return $metaResponses;
}

    public static function updateBranchMeta($field, $data, $branch_id, &$metaResponses)
    {
        $templateFields = [
            'id_card_template',
            'receipt_template',
            'report_card_template',
        ];

        if (in_array($field, $templateFields) && isset($data[$field])) {
            $templateValue = $data[$field];

            $branchMeta = Branchmeta::where('branch_id', $branch_id)
                ->where('name', $field)
                ->first();

            if ($branchMeta) {
                $branchMeta->update(['value' => $templateValue]);
            } else {
                $branchMeta = Branchmeta::create([
                    'branch_id' => $branch_id,
                    'name' => $field,
                    'value' => $templateValue,
                ]);
            }
            $metaResponses[$field] = $branchMeta;
        }
    }

    public static function updatestudentfields($student_fields, $branch_id, $data)
    {
        foreach ($student_fields as $field_name) {
            $branchMeta = Branchmeta::where('branch_id', $branch_id)
                ->where('name', $field_name)
                ->first();
            $new_value = ($data['report_card_template'] == 1) ? 1 : 0;

            if ($branchMeta) {
                $branchMeta->update(['value' => $new_value]);
            } else {
                Branchmeta::create([
                    'branch_id' => $branch_id,
                    'name' => $field_name,
                    'value' => $new_value,
                ]);
            }
        }
    }
}
