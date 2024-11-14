<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\DateService;
use Carbon\Carbon;

class ExamConfig extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'exam_report_config';
    protected $fillable = [
        'exam_id',
        'class_id',
        'section_id',
        'subject_id',
        'max_marks',
        'pass_marks',
        'is_grade',
        'is_average',
        'add_in_grand',
        'internal',
        'external',
        'topper_visible',
        'rank_visible',
        'sequence',
        'lock_report'
    ];

    protected $casts = [
        'is_grade' => 'integer',
        'topper_visible' => 'integer',
        'rank_visible' => 'integer',
    ];

    public function createExamConfig($validatedData)
    {
        $dateService = new DateService();
        foreach ($validatedData['section_id'] as $sectionId) {
            foreach ($validatedData['subjects'] as $data) {
                $configData[] = [
                    'exam_id' => $validatedData['exam_id'],
                    'class_id'=> $validatedData['class_id'],
                    'section_id'=> $sectionId,
                    'subject_id' => $data['subject_id'],
                    'max_marks' => $data['max_marks'],
                    'pass_marks' => $data['pass_marks'],
                    'is_grade'=> $validatedData['is_grade'] ?? 0,
                    'topper_visible'=> $validatedData['topper_visible'] ?? 0,
                    'rank_visible'=> $validatedData['rank_visible'] ?? 0,
                    'sequence'=> $data['sequence'] ?? 0,
                    'is_average' => $data['is_average'],
                    'add_in_grand' => $data['add_in_grand'],
                    'internal' => $data['internal'] ?? 0,
                    'external' => $data['external'] ?? 0,
                    'lock_report' => isset($validatedData['lock_report']) ? Carbon::now()->addDays((int) $validatedData['lock_report']) : null
                ];
            }
        }
        ExamConfig::insert($configData);
    }

    public function updateExamConfig($data, $id)
    {
        $examConfig = ExamConfig::find($id);
       $examConfig->update(
        [
            'max_marks' => $data['max_marks'],
            'pass_marks' => $data['pass_marks'],
            'is_grade'=> $data['is_grade'] ?? 0,
            'topper_visible'=> $data['topper_visible'] ?? 0,
            'rank_visible'=> $data['rank_visible'] ?? 0,
            'sequence'=> $data['sequence'] ?? 0,
            'is_average' => $data['is_average'],
            'add_in_grand' => $data['add_in_grand'],
            'internal' => $data['internal'] ?? 0,
            'external' => $data['external'] ?? 0,
            'lock_report' => isset($data['lock_report']) ? Carbon::now()->addDays((int) $data['lock_report']) : $examConfig->lock_report
        ]);
    }
}
