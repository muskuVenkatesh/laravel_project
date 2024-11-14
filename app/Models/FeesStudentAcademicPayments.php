<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\FeesAcademicPayments;
use App\Services\DateService;
use App\Models\FeesAcademicSetup;
use Carbon\Carbon;

class FeesStudentAcademicPayments extends Model
{
    use HasFactory;

    protected $table = 'fees_student_academic_payments';
    protected $fillable = [
        'school_id',
        'branch_id',
        'class_id',
        'section_id',
        'academic_id',
        'fee_academic_id',
        'student_id',
        'fees_details',
        'due_date',
        'amount',
        'discount',
        'amount_to_pay',
        'amount_paid',
        'balance',
        'fine',
        'paid_status',
        'pay_timeline',
        'pay_timeline_date',
        'status',
    ];

    public static function createStudentAcademicPayments($fees_academic_id,  $data)
    {
        $student_ids = Student::where('branch_id', $data['branch_id'])
            ->where('class_id', $data['class_id'])
            ->where('section_id', $data['section_id'])
            ->pluck('id');
        if ($student_ids->isNotEmpty()) {
            $dateService = new DateService();

            $payTimelineDate = $dateService->formatDate($data['pay_timeline_date']);
            $term1_date = Carbon::parse($payTimelineDate);
            $term2_date = $dateService->IncressMonth($payTimelineDate, 3);
            $term3_date = $dateService->IncressMonth($payTimelineDate, 6);

            if($term1_date != ''  && $term2_date != '' && $term3_date != '')
            {
                FeesStudentAcademicPayments::insertQuaterWise($student_ids, $term1_date, $term2_date, $term3_date, $fees_academic_id, $data);
            }
        }
        else
        {
            $delete = FeesAcademicSetup::find($fees_academic_id);
            $delete->delete();
        }
    }

    public static function updateAcademicPayments($id,  $data)
    {
        FeesStudentAcademicPayments::updateQuaterWise($id, $data);
    }

    public static function insertQuaterWise($student_ids, $term1_date, $term2_date, $term3_date, $fees_academic_id, $data)
    {
        foreach ($student_ids as $student_id)
        {
            FeesStudentAcademicPayments::create([
                'school_id' => $data['school_id'],
                'branch_id' => $data['branch_id'],
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'],
                'academic_id' => $data['academic_id'],
                'fee_academic_id' => $fees_academic_id,
                'student_id' => $student_id,
                'fees_details' => 'TERM TUITION FEE - TERM I',
                'due_date' => $term1_date,
                'amount' => round($data['amount'] / 3, 2),
                'discount' => round($data['discount'] / 3, 2),
                'amount_to_pay' => round(($data['amount'] - $data['discount']) / 3, 2),
                'amount_paid' => 0,
                'balance' => round(($data['amount'] - $data['discount']) / 3, 2),
                'fine' => 0,
                'paid_status' => 'unpaid',
                'pay_timeline' => $data['pay_timeline'],
                'pay_timeline_date' => $term1_date,
                'status' => 'pending',
            ]);
            FeesStudentAcademicPayments::create([
                'school_id' => $data['school_id'],
                'branch_id' => $data['branch_id'],
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'],
                'academic_id' => $data['academic_id'],
                'fee_academic_id' => $fees_academic_id,
                'student_id' => $student_id,
                'fees_details' => 'TERM TUITION FEE - TERM II',
                'due_date' => $term2_date,
                'amount' => round($data['amount'] / 3, 2),
                'discount' => round($data['discount'] / 3, 2),
                'amount_to_pay' => round(($data['amount'] - $data['discount']) / 3, 2),
                'amount_paid' => 0,
                'balance' => round(($data['amount'] - $data['discount']) / 3, 2),
                'fine' => 0,
                'paid_status' => 'unpaid',
                'pay_timeline' => $data['pay_timeline'],
                'pay_timeline_date' => $term2_date,
                'status' => 'pending',
            ]);
            FeesStudentAcademicPayments::create([
                'school_id' => $data['school_id'],
                'branch_id' => $data['branch_id'],
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'],
                'academic_id' => $data['academic_id'],
                'fee_academic_id' => $fees_academic_id,
                'student_id' => $student_id,
                'fees_details' => 'TERM TUITION FEE - TERM III',
                'due_date' => $term3_date,
                'amount' => round($data['amount'] / 3, 2),
                'discount' => round($data['discount'] / 3, 2),
                'amount_to_pay' => round(($data['amount'] - $data['discount']) / 3, 2),
                'amount_paid' => 0,
                'balance' => round(($data['amount'] - $data['discount']) / 3, 2),
                'fine' => 0,
                'paid_status' => 'unpaid',
                'pay_timeline' => $data['pay_timeline'],
                'pay_timeline_date' => $term3_date,
                'status' => 'pending',
            ]);
        }
    }

    public static function updateQuaterWise($id, $data)
    {
        $students = FeesStudentAcademicPayments::where('fee_academic_id', $id)
        ->where('paid_status', 'unpaid')
        ->get(['student_id', 'id']);

        $existingPayments = FeesAcademicPayments::whereIn('fees_st_pay_id', $students->pluck('id'))
            ->pluck('fees_st_pay_id')
            ->toArray();

        $unpaidStudents = $students->filter(function ($student) use ($existingPayments) {
            return !in_array($student->id, $existingPayments);
        });
        $studentCounts = [];
        foreach ($unpaidStudents as $student) {
            if (!isset($studentCounts[$student->student_id])) {
                $studentCounts[$student->student_id] = 0;
            }
            $studentCounts[$student->student_id]++;
        }
        foreach ($unpaidStudents as $student) {
            $count = $studentCounts[$student->student_id];

            FeesStudentAcademicPayments::where('id', $student->id)
                ->update([
                    'amount' => round($data['amount'] / $count, 2),
                    'discount' => round($data['discount'] / $count, 2),
                    'amount_to_pay' => round(($data['amount'] - $data['discount']) / $count, 2),
                    'balance' => round(($data['amount'] - $data['discount']) / $count, 2),
                ]);
        }
    }
}
