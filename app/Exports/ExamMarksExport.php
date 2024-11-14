<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExamMarksExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $flattenedData = [];

        foreach ($this->data as $item) {
            $present_count = $item['attendance']['present_count'] ?? 0;
            $absent_count = $item['attendance']['absent_count'] ?? 0;

            $class_info = $item['marks_data']['class_info'] ?? [];
            $marks_info = $item['marks_data']['marks'] ?? [];

            foreach ($marks_info as $mark) {
                $flattenedData[] = [
                    'ID' => $item['id'] ?? null,
                    'Branch Name' => $item['branch_name'] ?? null,
                    'Branch ID' => $item['branch_id'] ?? null,
                    'Student ID' => $item['student_id'] ?? null,
                    'Student Name' => $item['student_name'] ?? null,
                    'Admission No' => $item['admission_no'] ?? null,
                    'Is Locked' => $item['is_Lock'] ?? null,
                    'Present Count' => $present_count,
                    'Absent Count' => $absent_count,
                    'Class ID' => $class_info['class_id'] ?? null,
                    'Class Name' => $class_info['class_name'] ?? null,
                    'Section ID' => $class_info['section_id'] ?? null,
                    'Section Name' => $class_info['section_name'] ?? null,
                    'Exam ID' => $class_info['exam_id'] ?? null,
                    'Academic ID' => $class_info['academic_id'] ?? null,
                    'Entry Type' => $class_info['entry_type'] ?? null,
                    'Subject ID' => $mark['subject_id'] ?? null,
                    'Subject Name' => $mark['subject_name'] ?? null,
                    'Is Absent' => $mark['isabsent'] ?? null,
                    'Internal Marks' => $mark['internal'] ?? null,
                    'External Marks' => $mark['external'] ?? null,
                    'Total Marks' => ($mark['internal'] ?? 0) + ($mark['external'] ?? 0),
                    'Percentage' => $item['marks_data']['percentage'] ?? null,
                    'Result' => $item['marks_data']['result'] ?? null,
                    'Section Rank' => $item['marks_data']['section_rank'] ?? null,
                    'Class Rank' => $item['marks_data']['class_rank'] ?? null,
                ];
            }
        }

        return collect($flattenedData);
    }

    public function headings(): array
    {
        return [
            'ID', 'Branch Name', 'Branch ID', 'Student ID', 'Student Name', 'Admission No',
            'Is Locked', 'Present Count', 'Absent Count', 'Class ID', 'Class Name',
            'Section ID', 'Section Name', 'Exam ID', 'Academic ID', 'Entry Type',
            'Subject ID', 'Subject Name', 'Is Absent', 'Internal Marks', 'External Marks',
            'Total Marks', 'Percentage', 'Result', 'Section Rank', 'Class Rank'
        ];
    }
}
