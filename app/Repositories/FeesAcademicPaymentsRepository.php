<?php
namespace App\Repositories;

use App\Models\FeesAcademicPayments;
use App\Models\FeesStudentAcademicPayments;
use App\Interfaces\FeesAcademicPaymentsInterface;
use Illuminate\Http\Request;
use App\Services\DateService;
use Illuminate\Support\Str;
use App\Models\SchoolBrancheSettings;

class FeesAcademicPaymentsRepository implements FeesAcademicPaymentsInterface
{
    public function __construct(FeesAcademicPayments $feesacademicpayments)
    {
        $this->feesacademicpayments = $feesacademicpayments;
    }
    public function createAcademicPayments($data)
    {
        $dateService = new DateService();
        $disount = $this->feesacademicpayments->where('fees_st_pay_id', $data['fees_st_pay_id'])
        ->where('student_id', $data['student_id'])
        ->value('discount') ? 0 : $data['discount'];

        $fees_amount = $this->feesacademicpayments->where('fees_st_pay_id', $data['fees_st_pay_id'])
        ->where('student_id', $data['student_id'])
        ->value('amount_to_pay') ?? $data['fees_amount'];

        $balance = $this->feesacademicpayments->where('fees_st_pay_id', $data['fees_st_pay_id'])
        ->where('student_id', $data['student_id'])
        ->orderBy('id', 'desc')
        ->value('balance');
        if ($balance !== null) {
            $balances = $balance - $data['amount_paid'];
        }
        else
        {
            $balances = $data['amount_to_pay'] - $data['amount_paid'];
            $amount_to_pay = $data['amount_to_pay'];
        }
        $transaction_id = $this->getTransactionId();
        $datas = [
            'fees_st_pay_id' => $data['fees_st_pay_id'],
            'student_id' => $data['student_id'],
            'transaction_id' => $transaction_id  ?? null,
            'fees_amount' => $fees_amount,
            'discount' => $disount,
            'amount_to_pay' => $amount_to_pay ?? $balance,
            'amount_paid' => $data['amount_paid'],
            'balance' => $balances,
            'paid_status' => 'paid',
            'payment_date' => now()
        ];
        $feespayment = $this->feesacademicpayments->createAcademicPayments($balances, $datas);

        if($datas['amount_to_pay'] < $datas['amount_paid'])
        {
            $this->secondEMIT($data, $feespayment->amount_to_pay, $feespayment->id, $transaction_id);
        }
        return "Transaction Successfully.";
    }

    public function secondEMIT($data, $balance, $feespaymentId, $transaction_id)
    {
        $fees_st_pay_id = $data['fees_st_pay_id'] + 1;
        $disount = FeesAcademicPayments::where('fees_st_pay_id', $fees_st_pay_id)
        ->where('student_id', $data['student_id'])
        ->value('discount') ? 0 : $data['discount'];

        $fees_amount = FeesAcademicPayments::where('fees_st_pay_id', $fees_st_pay_id)
        ->where('student_id', $data['student_id'])
        ->value('amount_to_pay') ?? $data['fees_amount'];

        $balancee = FeesAcademicPayments::where('fees_st_pay_id', $fees_st_pay_id)
        ->where('student_id', $data['student_id'])
        ->orderBy('id', 'desc')
        ->value('balance');

        $amount_paid = $data['amount_paid'] - $balance;
        if ($balancee !== null) {
            $balances = $balancee - $data['amount_paid'];
        }
        else
        {
            $balances = $data['amount_to_pay'] - $amount_paid;
            $amount_to_pay = $data['amount_to_pay'];
        }
        $feepayment = FeesAcademicPayments::find($feespaymentId);
        $feepayment->amount_paid = $balance;
        $feepayment->balance = 0;
        $feepayment->save();
        $SecondFeePayment = [
            'fees_st_pay_id' => $fees_st_pay_id,
            'student_id' => $data['student_id'],
            'transaction_id' => $transaction_id ?? null,
            'fees_amount' => $fees_amount,
            'discount' => $disount,
            'amount_to_pay' => $amount_to_pay ?? $balancee,
            'amount_paid' => $amount_paid,
            'balance' => $balances,
            'paid_status' => 'paid',
            'payment_date' => now()
        ];
        $SecondFeePayments = $this->feesacademicpayments->createAcademicPayments($balances, $SecondFeePayment);
    }

    public function getAcademicPayments(Request $request)
    {
        $student_id = $request->input('student_id');
        $limit = $request->input('_limit');
        $dateService = new DateService();
        $allpaymentQuery = FeesAcademicPayments::where('fees_academic_payments.student_id', $student_id)
        ->join('fees_student_academic_payments', 'fees_student_academic_payments.id', '=', 'fees_academic_payments.fees_st_pay_id')
        ->select(
            'fees_student_academic_payments.fees_details',
            'fees_academic_payments.amount_to_pay',
            'fees_academic_payments.amount_paid',
            'fees_academic_payments.balance',
            'fees_academic_payments.paid_status',
            'fees_academic_payments.payment_date'
        );

        if ($request->has('q')) {
            $search = $request->input('q');
            $allpaymentQuery->where('fees_academic_payments.amount_paid', 'like', "%{$search}%")
            ->orWhere('fees_academic_payments.amount_to_pay', 'like', "%{$search}%");
        }

        if ($limit <= 0) {
            $allpayment = $allpaymentQuery->get();
        } else {
            $allpaymentPaginated = $allpaymentQuery->paginate($limit);
            $allpayment = collect($allpaymentPaginated->items());
        }
        $allpayment->map(function($payment) use ($dateService) {
            $payment->payment_date = $dateService->databaseDateFormate($payment->payment_date);
            return $payment;
        });

        $total = $allpayment->count();
        return [
            'data' => $allpayment,
            'total' => $total
        ];
    }

    public function getTransactionId(){
        $newTransactionId = 'TRX' . mt_rand(10000000, 99999999);

        while (FeesAcademicPayments::where('transaction_id', $newTransactionId)->exists()) {
            $newTransactionId = 'TRX' . mt_rand(10000000, 99999999);
        }
        return $newTransactionId;
    }

    public function getAcademicPaymentsMethod(Request $request)
    {
        $branchId = $request->input('branch_id');
        $paymentMethod = SchoolBrancheSettings::where('school_branche_settings.branch_id', $branchId)
        ->join('fees_pay_types', 'fees_pay_types.id', 'school_branche_settings.offline_payments')
        ->select('school_branche_settings.offline_payments as id', 'fees_pay_types.name as type')
        ->get();
        return $paymentMethod;
    }
}
