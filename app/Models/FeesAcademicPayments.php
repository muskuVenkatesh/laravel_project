<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Services\DateService;
use App\Models\FeesStudentAcademicPayments;
use Carbon\Carbon;

class FeesAcademicPayments extends Model
{
    use HasFactory;

    protected $table = 'fees_academic_payments';
    protected $fillable = [
        'fees_st_pay_id',
        'student_id',
        'transaction_id',
        'fees_amount',
        'discount',
        'amount_to_pay',
        'amount_paid',
        'balance',
        'fine',
        'paid_status',
        'payment_date',
        'status'
    ];

    public function createAcademicPayments($balance, $data)
    {
        if($data['amount_to_pay'] <= $data['amount_paid'])
        {
            $feesStudent = FeesStudentAcademicPayments::find($data['fees_st_pay_id']);
            $feesStudent->paid_status = 'paid';
            $feesStudent->save();
        }
       return FeesAcademicPayments::create($data);
    }
}
