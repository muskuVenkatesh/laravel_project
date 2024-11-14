<?php

namespace App\Repositories;

use App\Models\Parents;
use Illuminate\Http\Request;
use App\Models\FeesAcademicSetup;
use App\Models\FeesStudentAcademicPayments;
use App\Models\FeesAcademicPayments;
use App\Models\TransportDetails;
use App\Models\TransportRouteStop;
use App\Models\Branches;
use App\Models\Classes;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\DataNotFoundException;
use App\Interfaces\FeesAcademicSetupInterface;
use App\Jobs\FeesStudentAcademicPaymentsJob;
use App\Jobs\FeesStudentAcademicPaymentsUpdateJob;
use DB;
use App\Services\DateService;

class FeesAcademicSetupRepository implements FeesAcademicSetupInterface
{
    protected $feesscademicsetup;
    protected $feesacademicpayments;
    protected $dateService;

    public function __construct(FeesAcademicSetup $feesscademicsetup, FeesStudentAcademicPayments $feesacademicpayments,  DateService $dateService)
    {
        $this->feesscademicsetup = $feesscademicsetup;
        $this->feesacademicpayments = $feesacademicpayments;
        $this->dateService = $dateService;
    }

    public function createAcademicSetup($data)
    {
        $alreadySetup = $this->feesscademicsetup->where('class_id', $data['class_id'])
        ->where('section_id', $data['section_id'])->first();
        if($alreadySetup)
        {
            return "Setup Already Exist.";
        }
        $fees_academic_id = $this->feesscademicsetup->createAcademicSetup($data);
        if($fees_academic_id != '')
        {
            FeesStudentAcademicPaymentsJob::dispatch($fees_academic_id,  $data);
            return "Created Successfully.";
        }
    }
    public function getAcademicSetup(Request $request)
    {
        $limit = $request->input('_limit');
        $branch_id = $request->input('branch_id');
        $allacademicsetup = FeesAcademicSetup::where('fees_academic_setups.status', 1)
        ->where('fees_academic_setups.branch_id', $branch_id)
        ->join('branches', 'branches.id', '=', 'fees_academic_setups.branch_id')
        ->join('classes', 'classes.id', '=', 'fees_academic_setups.class_id')
        ->join('sections', 'sections.id', '=', 'fees_academic_setups.section_id')
        ->join('fees_timeline', 'fees_timeline.id', '=', 'fees_academic_setups.pay_timeline')
        ->join('academic_details', 'academic_details.id', '=', 'fees_academic_setups.academic_id')
        ->leftJoin('fees_discount_type','fees_discount_type.id', '=', 'fees_academic_setups.discount_type')
        ->select('branches.branch_name',
        DB::raw("CONCAT(TO_CHAR(academic_details.start_date, 'Mon YYYY'), ' - ', TO_CHAR(academic_details.end_date, 'Mon YYYY')) as academic_year"),
        'fees_academic_setups.*',
        'fees_timeline.name as timeline_name',
        'classes.name as class_name',
        'sections.name as section_name',
        'fees_discount_type.name as discount_name');

        $total = $allacademicsetup->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $allacademicsetup->where('fees_academic_setups.amount', 'like', "%{$search}%");
        }
        if($request->has('school_id'))
        {
            $allacademicsetup->where('fees_academic_setups.school_id', $request->input('school_id'));
        }
        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allacademicsetup->orderBy($sortBy, $sortOrder);
        } else {
            $allacademicsetup->orderBy('fees_academic_setups.created_at', 'asc');
        }
        if ($limit <= 0) {
            $allacademicsetupData = $allacademicsetup->get();
        } else {
            $allacademicsetupData = $allacademicsetup->paginate($limit);
            $allacademicsetupData = $allacademicsetupData->items();
        }
        return ['data'=>$allacademicsetupData, 'total'=>$total];
    }

    public function getAcademicSetupById($id)
    {
        return FeesAcademicSetup::find($id);
    }

    public function updateAcademicSetup($data, $id)
    {
        $fees_academic_id = $this->feesscademicsetup->find($id);
        if($fees_academic_id)
        {
            $this->feesscademicsetup->updateAcademicPayments($data, $id);
            FeesStudentAcademicPaymentsUpdateJob::dispatch($id,  $data);
            return "Updated Successfully.";
        }
    }

    public function getStudentAcademicFees(Request $request)
    {
        $parent_id = $request->input('parent_id');
        if ($parent_id) {
            $student = Parents::where('parents.id', $parent_id)
                ->join('students', 'students.parent_id', '=', 'parents.id')
                ->select('students.id as student_id')->first();

            $student_ids = $student->student_id;
        } else {
            $student_ids = $request->input('student_id');
        }
        $data['academic_fees'] = $this->getacademicfees($student_ids);
        $data['transport_fees'] = $this->gettransportfees($student_ids);

        return $data;
    }

    public function gettransportfees($student_ids)
    {
       $dateservice = new DateService();
       $fees_data = FeesStudentAcademicPayments::where('fees_student_academic_payments.student_id', $student_ids)
            ->join('academic_details', 'academic_details.id', '=', 'fees_student_academic_payments.academic_id')
            ->select(
                'academic_details.start_date',
                'academic_details.end_date',
                'fees_student_academic_payments.due_date',
            )
            ->orderBy('fees_student_academic_payments.id', 'asc')
            ->first();
        $stop_id = TransportDetails::where('student_id', $student_ids)->value('stop_id');
        if ($stop_id){
            $stopData = TransportRouteStop::value('stop_data');
            $stop = current(array_filter($stopData, fn($s) => $s['stop_id'] == $stop_id));
            $transport_amount = $stop ? $stop['amount'] : 0;
            $start_date = $dateservice->dateMonthYearFormat($fees_data->start_date);
            $end_date = $dateservice->dateMonthYearFormat($fees_data->end_date);
            $academic_period = $start_date . ' - ' . $end_date;
            $transport_data = [
            "fees_details"=> "Transport Fee",
            "amount"=> $transport_amount,
            "due_date" => $fees_data->due_date,
            "discount"=> 0,
            "fine" => 0,
            "academic_period" => $academic_period,
            "amount_to_pay"=> $transport_amount,
            "balance"=> $transport_amount];

            return $transport_data;
        }
        return "Transport Not Assigned";
    }

    public function getacademicfees($student_ids)
    {
       $data = FeesStudentAcademicPayments::where('fees_student_academic_payments.student_id', $student_ids)
            ->join('academic_details', 'academic_details.id', '=', 'fees_student_academic_payments.academic_id')
            ->select(
                'fees_student_academic_payments.id',
                'academic_details.start_date',
                'academic_details.end_date',
                'fees_student_academic_payments.fees_details',
                'fees_student_academic_payments.due_date',
                'fees_student_academic_payments.amount',
                'fees_student_academic_payments.discount',
                'fees_student_academic_payments.amount_to_pay',
                'fees_student_academic_payments.balance',
                'fees_student_academic_payments.fine',
                'fees_student_academic_payments.paid_status'
            )
            ->orderBy('fees_student_academic_payments.id', 'asc')
            ->get();

        if ($data->isNotEmpty()) {
            $payments = $data->map(function($payment) {
                $dateservice = new DateService();
                $start_date = $dateservice->dateMonthYearFormat($payment->start_date);
                $end_date = $dateservice->dateMonthYearFormat($payment->end_date);
                $payment->academic_period = $start_date . ' - ' . $end_date;

                $totalAmountPaid = FeesAcademicPayments::where('fees_st_pay_id', $payment->id)->sum('amount_paid');
                $payment->amount_paid = $totalAmountPaid;
                $payment->balance = $payment->amount_to_pay - $totalAmountPaid;
                return $payment;
            });
            return $payments;
        }
    }

    public function getFeesDashboard(Request $request)
    {
        $school_id = $request->input('school_id');
        $branch_id = $request->input('branch_id');
        $total_class = '';
        $total_branches = '';
        $today = $this->dateService->formatDate(date('d/m/Y'));
        $query = $this->feesacademicpayments
            ->join('students', 'students.id', '=', 'fees_student_academic_payments.student_id')
            ->whereDate('fees_student_academic_payments.due_date', '<', $today)
            ->where('fees_student_academic_payments.paid_status', '!=', 'paid')
            ->select(
                'students.first_name',
                'students.middle_name',
                'students.last_name',
                'fees_student_academic_payments.balance'
            );
        if ($branch_id != '' & $school_id != '')
        {
            $query->where('fees_student_academic_payments.school_id', $school_id)
            ->where('fees_student_academic_payments.branch_id', $branch_id);
        }
        else if ($school_id) {
            $query->where('fees_student_academic_payments.school_id', $school_id);
        }
        $students_with_due_balance = $query->get();
        if ($branch_id != '' & $school_id != '') {
            $totalClass = Classes::where('branch_id', $branch_id)->get();
            $classIds = $totalClass->pluck('id');
            $total_setup = $this->feesscademicsetup
                ->where('branch_id', $branch_id)
                ->whereIn('class_id', $classIds);
            $total_class = $totalClass;
            $total_fee_collection = $this->getTotalFeeCollection($school_id, $branch_id);
            $total_onlinefee_collection = $this->getTotalFeeCollection($school_id, $branch_id);
        }
        else if($school_id){
            $total_setup = $this->getTotalSetup($school_id);
            $total_branches = Branches::where('school_id', $school_id);
            $total_fee_collection = $this->getTotalFeeCollection($school_id);
            $total_onlinefee_collection = $this->getTotalFeeCollection($school_id);
        }
        else {
            $total_setup = $this->feesscademicsetup;
            $total_branches = Branches::all();
            $total_fee_collection = $this->getTotalFeeCollection($school_id = '');
            $total_onlinefee_collection = $this->getTotalFeeCollection($school_id = '');
        }

        return [
            'total_branches' => $total_branches ? $total_branches->count() : 1,
            'total_class' => $total_class ? $total_class->count() : 0,
            'total_setup' => $total_setup->count(),
            'total_fee_collection' => $total_fee_collection,
            'total_online_collection' => $total_onlinefee_collection,
            'students_with_due_balance' => $students_with_due_balance
        ];
    }

    public function getTotalSetup($school_id)
    {
        return $this->feesscademicsetup->where('school_id', $school_id);
    }

    public function getTotalFeeCollection($school_id = '', $branch_id = '')
    {
        if($branch_id != '' && $school_id != '')
        {
            $totalFee = $this->feesacademicpayments
            ->where('fees_student_academic_payments.school_id', $school_id)
            ->where('fees_student_academic_payments.school_id', $branch_id)
            ->where('fees_academic_payments.paid_status', 'paid')
            ->join('fees_academic_payments', 'fees_academic_payments.fees_st_pay_id', '=', 'fees_student_academic_payments.id')
            ->sum('fees_academic_payments.amount_paid');
        }
        else if($school_id)
        {
            $totalFee = $this->feesacademicpayments
            ->where('fees_student_academic_payments.school_id', $school_id)
            ->where('fees_academic_payments.paid_status', 'paid')
            ->join('fees_academic_payments', 'fees_academic_payments.fees_st_pay_id', '=', 'fees_student_academic_payments.id')
            ->sum('fees_academic_payments.amount_paid');
        }
        else
        {
            $totalFee = $this->feesacademicpayments
            ->where('fees_academic_payments.paid_status', '=', 'paid')
            ->join('fees_academic_payments', 'fees_academic_payments.fees_st_pay_id', '=', 'fees_student_academic_payments.id')
            ->sum('fees_academic_payments.amount_paid');
        }


        return $totalFee;
    }
}
