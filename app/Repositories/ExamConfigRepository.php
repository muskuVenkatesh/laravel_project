<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\ExamConfig;
use App\Interfaces\ExamConfigInterface;
class ExamConfigRepository implements ExamConfigInterface
{
    protected $examconfig;
    public function __construct(ExamConfig $examconfig)
    {
        $this->examconfig = $examconfig;
    }

    public function createExamConfig($data)
    {
        $createdExam = $this->examconfig->createExamConfig($data);
        return $createdExam;
    }

    public function getAllExamConfig(Request $request)
    {
        $limit = $request->input('_limit');
        $class_id = $request->input('class_id');
        $section_id = $request->input('section_id');
        $exam_id = $request->input('exam_id');

        $allexamconfig = ExamConfig::withoutTrashed()
        ->join('exams', 'exams.id', '=', 'exam_report_config.exam_id')
        ->join('classes', 'classes.id', '=', 'exam_report_config.class_id')
        ->join('sections', 'sections.id', '=', 'exam_report_config.section_id')
        ->join('subjects', 'subjects.id', '=', 'exam_report_config.subject_id')
        ->where('exam_report_config.class_id', $class_id)
        ->where('exam_report_config.section_id', $section_id)
        ->where('exam_report_config.exam_id', $exam_id)
        ->select('exam_report_config.*', 'exams.name as exam_name', 'classes.name as class_name', 'sections.name as section_name', 'subjects.name as subject_name');

        if ($request->has('q')) {
            $search = $request->input('q');
            $allexamconfig->where('name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allexamconfig->orderBy($sortBy, $sortOrder);
        } else {
            $allexamconfig->orderBy('created_at', 'asc');
        }

        if ($limit <= 0) {
            $allexamconfigData = $allexamconfig->get();
        } else {
            $allexamconfigData = $allexamconfig->paginate($limit);
            $allexamconfigData = $allexamconfigData->items();
        }
        $total = $allexamconfig->count();
        return ['data'=>$allexamconfigData,'total'=>$total];
    }

    public function getExamConnfigById($id)
    {
        return $this->examconfig->find($id);
    }

    public function updateExamConfig($id,$data)
    {
        $examconfig = $this->examconfig->find($id);
        if ($examconfig) {
            $this->examconfig->updateExamConfig($data, $id);
            return $examconfig;
        }
        return null;
    }

    public function softDeleteExamConfig($id)
    {
        $exam = $this->examconfig->find($id);
        if ($exam) {
            $exam->delete();
            return $exam;
        }
    }
}
