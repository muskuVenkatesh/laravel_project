<?php

namespace App\Models;

use App\Models\User;
use App\Models\SchoolBrancheSettings;
use App\Models\Student;
use App\Models\Branches;
use App\Models\Occupation;
use App\Models\Qualifications;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Parents extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'branch_id', 'parent_uid', 'first_name', 'middle_name', 'last_name',
        'phone', 'alt_phone', 'alt_email', 'education', 'occupation', 'annual_income',
        'mother_name', 'mother_phone', 'mother_email', 'mother_education', 'mother_occupation',
        'mother_annual_income', 'mother_aadhaar_no', 'mother_pan_card', 'mother_dob'
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    public static function createParent($data,$user_id)
    {
       $parent = Parents::create([
            'branch_id' => $data['branch_id'],
            'user_id' => $user_id,
            'parent_uid' => Str::uuid(),
            'first_name' => $data['pfirst_name'],
            'middle_name' => $data['pmiddle_name'] ?? null,
            'last_name' => $data['plast_name'],
            'phone' => $data['phone'],
            'alt_email' => $data['email']
        ]);
       return $parent->id;
    }

    public static function updateUsername($parent_id,$parentCode)
    {
        $parent_user_id = Parents::where('id', $parent_id)->value('user_id');
        $user = User::find($parent_user_id);
        $user->username = $parentCode;
        $user->save();
    }

    public static function updateParent($id, $data)
    {

        $parent = Parents::findOrFail($id);
        $parent->update([
            'branch_id' => $data['branch_id'] ?? $parent->branch_id,
            'user_id' => $parent->user_id,
            'student_id' => $data['student_id'] ?? $parent->student_id,
            'first_name' => $data['first_name'] ?? $parent->first_name,
            'middle_name' => $data['middle_name'] ?? $parent->middle_name,
            'last_name' => $data['last_name'] ?? $parent->last_name,
            'phone' => $data['phone'] ?? $parent->phone,
            'alt_phone' => $data['alt_phone'] ?? $parent->alt_phone,
            'alt_email' => $data['alt_email'] ?? $parent->alt_email,
            'education' => $data['education'] ?? $parent->education,
            'occupation' => $data['occupation'] ?? $parent->occupation,
            'annual_income' => $data['annual_income'] ?? $parent->annual_income,
            'mother_name' => $data['mother_name'] ?? $parent->mother_name,
            'mother_phone' => $data['mother_phone'] ?? $parent->mother_phone,
            'mother_email' => $data['mother_email'] ?? $parent->mother_email,
            'mother_education' => $data['mother_education'] ?? $parent->mother_education,
            'mother_occupation' => $data['mother_occupation'] ?? $parent->mother_occupation,
            'mother_annual_income' => $data['mother_annual_income'] ?? $parent->mother_annual_income,
            'mother_aadhaar_no' => $data['mother_aadhaar_no'] ?? $parent->mother_aadhaar_no,
            'mother_pan_card' => $data['mother_pan_card'] ?? $parent->mother_pan_card,
            'mother_dob' => $data['mother_dob'] ?? $parent->mother_dob,
        ]);

        return $parent;
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }


    public function student()
    {
        return $this->belongsTo(Student::class,'student_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class,'branch_id');
    }

    public function education()
    {
        return $this->belongsTo(Qualifications::class, 'education');
    }

    public function motherEducation()
    {
        return $this->belongsTo(Qualifications::class, 'mother_education');
    }

    public function occupation()
    {
        return $this->belongsTo(Occupation::class, 'occupation');
    }

    public function motherOccupation()
    {
        return $this->belongsTo(Occupation::class, 'mother_occupation');
    }
}
