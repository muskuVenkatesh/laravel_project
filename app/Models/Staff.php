<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SchoolBrancheSettings;
use App\Models\Branches;
use App\Models\User;
use Carbon\Carbon;

class Staff extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'branch_id',
        'user_id',
        'employee_no',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'epf_no',
        'uan_no',
        'esi_no',
        'marital_status',
        'anniversary_date',
        'spouse_name',
        'kid_studying',
        'assigned_activity',
        'joining_date',
        'specialized',
        'department',
        'work_location',
        'qualification',
        'extra_qualification',
        'previous_school',
        'reason_change',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function createStaff($data,$user_id)
    {
        $joining_date = Carbon::createFromFormat('d/m/Y', $data['joining_date'])->format('Y-m-d');
        $staff = Staff::create([
            'branch_id' => $data['branch_id'],
            'user_id' => $user_id,
            'employee_no' => $data['employee_no'],
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'epf_no' => $data['epf_no'] ?? null,
            'uan_no' => $data['uan_no'] ?? null,
            'esi_no' => $data['esi_no'] ?? null,
            'marital_status' => $data['marital_status'] ?? null,
            'anniversary_date' => $data['anniversary_date'] ?? null,
            'spouse_name' => $data['spouse_name'] ?? null,
            'kid_studying' => $data['kid_studying'],
            'assigned_activity' => $data['assigned_activity'] ?? null,
            'joining_date' => $joining_date,
            'specialized' => $data['specialized']?? null,
            'department' => $data['department'],
            'work_location' => $data['work_location'] ?? null,
            'qualification' => $data['qualification'],
            'extra_qualification' => $data['extra_qualification'] ?? null,
            'previous_school' => $data['previous_school'] ?? null,
            'reason_change' => $data['reason_change'] ?? null,
        ]);
        $prfix = SchoolBrancheSettings::getPrifiex($data['branch_id']) ?? 3;
        $branch_code = Branches::getBranchCode($data['branch_id']);
        $staffCode = Staff::getstaffCode($staff->id, $branch_code,$prfix,$joining_date);

        $user = User::find($user_id);
        $user->username = $staffCode;
        $user->save();
    }

    public static function getstaffCode($staff_id, $branch_code,$prfix,$admission_date)
    {
       $admissiondate = substr($admission_date, 2, 2);
       return $branch_code . $admissiondate . 'T' . str_pad($staff_id, $prfix, '0', STR_PAD_LEFT);
    }

    public static function updateStaff($data, $id)
    {
        $joining_date = Carbon::createFromFormat('d/m/Y', $data['joining_date'])->format('Y-m-d');
        $Staff = Staff::find($id);
        $previous_joining_date = $Staff->joining_date;
        $Staff->update([
            'branch_id' => $data['branch_id'],
            'user_id' => $Staff->user_id,
            'employee_no' => $data['employee_no'],
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'epf_no' => $data['epf_no'] ?? null,
            'uan_no' => $data['uan_no'] ?? null,
            'esi_no' => $data['esi_no'] ?? null,
            'marital_status' => $data['marital_status'] ?? null,
            'anniversary_date' => $data['anniversary_date'] ?? null,
            'spouse_name' => $data['spouse_name'] ?? null,
            'kid_studying' => $data['kid_studying'],
            'assigned_activity' => $data['assigned_activity'] ?? null,
            'joining_date' => $joining_date,
            'specialized' => $data['specialized']?? null,
            'department' => $data['department'],
            'work_location' => $data['work_location'] ?? null,
            'qualification' => $data['qualification'],
            'extra_qualification' => $data['extra_qualification'] ?? null,
            'previous_school' => $data['previous_school'] ?? null,
            'reason_change' => $data['reason_change'] ?? null,
        ]);
        if($previous_joining_date != $joining_date)
        {
            $prfix = SchoolBrancheSettings::getPrifiex($data['branch_id']) ?? 3;
            $branch_code = Branches::getBranchCode($data['branch_id']);
            $staffCode = Staff::getstaffCode($id, $branch_code,$prfix,$joining_date);

            $user = User::find($Staff->user_id);
            $user->username = $staffCode;
            $user->save();
        }
    }
}
