<?php

namespace App\Models;

use App\Models\Parents;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolBrancheSettings;
use App\Models\Branches;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'branch_id',
        'academic_year_id',
        'parent_id',
        'roll_no',
        'first_name',
        'middle_name',
        'last_name',
        'fee_book_no',
        'place_of_birth',
        'mother_tongue',
        'physically_challenge',
        'neet_applicable',
        'transport_required',
        'medium_id',
        'class_id',
        'section_id',
        'group_id',
        'reg_no',
        'emis_no',
        'cse_no',
        'file_no',
        'admission_no',
        'application_no',
        'admission_date',
        'joining_quota',
        'first_lang_id',
        'second_lang_id',
        'third_lang_id',
        'achievements',
        'area_of_interest',
        'additional_skills',
        'image',
    ];

    public function parent()
    {
        return $this->belongsTo(Parent::class, 'parent_id');
    }

    public function medium()
    {
        return $this->belongsTo(Medium::class, 'medium_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function academicDetails()
    {
        return $this->belongsTo(AcademicDetail::class, 'academic_year_id');
    }

    public function createStudent($data, $parent)
    {
        $imagePath = null;
        if (isset($data['image']) && $data['image']->isValid()) {
            $imagePath = $data['image']->store('images', 'public');
        }
        $admissionDate = Carbon::createFromFormat('d/m/Y', $data['admission_date'])->format('Y-m-d');
        $student = Student::create([
            'branch_id' => $data['branch_id'] ?? null,
            'academic_year_id' => $data['academic_year_id'] ?? null,
            'parent_id' => $parent,
            'roll_no' => $data['roll_no'] ?? null,
            'first_name' => $data['stfirst_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['stlast_name'],
            'fee_book_no' => $data['fee_book_no'] ?? null,
            'place_of_birth' => $data['place_of_birth'] ?? null,
            'mother_tongue' => $data['mother_tongue'] ?? null,
            'physically_challenge' => $data['physically_challenge'] ?? null,
            'neet_applicable' => $data['neet_applicable'] ?? null,
            'transport_required' => $data['transport_required'] ?? null,
            'medium_id' => $data['medium_id'] ?? null,
            'class_id' => $data['class_id'] ?? null,
            'section_id' => $data['section_id'] ?? null,
            'group_id' => $data['group_id'] ?? null,
            'reg_no' => $data['reg_no'] ?? null,
            'emis_no' => $data['emis_no'] ?? null,
            'cse_no' => $data['cse_no'] ?? null,
            'file_no' => $data['file_no'] ?? null,
            'admission_no' => $data['admission_no'] ?? null,
            'application_no' => $data['application_no'] ?? null,
            'admission_date' => $admissionDate,
            'joining_quota' => $data['joining_quota'] ?? null,
            'first_lang_id' => $data['first_lang_id'] ?? null,
            'second_lang_id' => $data['second_lang_id'] ?? null,
            'third_lang_id' => $data['third_lang_id'] ?? null,
            'achievements' => $data['achievements'],
            'area_of_interest' => $data['area_of_interest'] ?? null,
            'additional_skills' => $data['additional_skills'] ?? null,
            'image' => $imagePath,
        ]);
        $student_id = $student->id;
        $prfix = SchoolBrancheSettings::getPrifiex($data['branch_id']) ?? 3;
        $branch_code = Branches::getBranchCode($data['branch_id']);
        $parentCode = Student::getparentCode($student_id, $branch_code, $parent, $prfix, $admissionDate);
        $studentID = Student::getstudentCode($student_id, $branch_code, $prfix, $admissionDate);
        $parent_user_id = Parents::updateUsername($parent, $parentCode);
        $studentupdate = Student::find($student_id);
        $studentupdate->user_id = $studentID;
        $studentupdate->save();
        return $studentID;
    }

    public static function getstudentCode($student_id, $branch_code, $prfix, $admission_date)
    {
        $admissiondate = substr($admission_date, 2, 2);
        $studentCode = $branch_code . $admissiondate . 'S' . str_pad($student_id, $prfix, '0', STR_PAD_LEFT);
        $user = User::create([
            'email' => $studentCode . '@gmail.com',
            'roleid' => 7,
            'status' => 1,
            'password' =>  Hash::make('password'),
        ]);
        $user->find($user->id);
        $user->username = $studentCode;
        $user->save();
        return $user->id;
    }
    public static function getparentCode($student_id, $branch_code, $parent, $prfix, $admission_date)
    {
        $admissiondate = substr($admission_date, 2, 2);
        return $branch_code . $admissiondate . 'P' . str_pad($parent, $prfix, '0', STR_PAD_LEFT);
    }
    public function updateStudent($id, $data)
    {
        $student = Student::findOrFail($id);
        $imagePath = $student->image;

        if (isset($data['image']) && $data['image']->isValid()) {
            // Remove old file if exists
            if ($imagePath && Storage::exists('public/' . $imagePath)) {
                Storage::delete('public/' . $imagePath);
            }
            $imagePath = $data['image']->store('images', 'public');
        }

        $student->update([
            'branch_id' => $data['branch_id'] ?? $student->branch_id,
            'parent_id' => $data['parent_id'] ?? $student->parent_id,
            'academic_year_id' => $data['academic_year_id'] ?? $student->academic_year_id,
            'roll_no' => $data['roll_no'] ?? $student->roll_no,
            'first_name' => $data['first_name'] ?? $student->first_name,
            'middle_name' => $data['middle_name'] ?? $student->middle_name,
            'last_name' => $data['last_name'] ?? $student->last_name,
            'fee_book_no' => $data['fee_book_no'] ?? $student->fee_book_no,
            'place_of_birth' => $data['place_of_birth'] ?? $student->place_of_birth,
            'mother_tongue' => $data['mother_tongue'] ?? $student->mother_tongue,
            'physically_challenge' => $data['physically_challenge'] ?? $student->physically_challenge,
            'neet_applicable' => $data['neet_applicable'] ?? $student->neet_applicable,
            'transport_required' => $data['transport_required'] ?? $student->transport_required,
            'medium_id' => $data['medium_id'] ?? $student->medium_id,
            'class_id' => $data['class_id'] ?? $student->class_id,
            'section_id' => $data['section_id'] ?? $student->section_id,
            'group_id' => $data['group_id'] ?? $student->group_id,
            'reg_no' => $data['reg_no'] ?? $student->reg_no,
            'emis_no' => $data['emis_no'] ?? $student->emis_no,
            'cse_no' => $data['cse_no'] ?? $student->cse_no,
            'file_no' => $data['file_no'] ?? $student->file_no,
            'admission_no' => $data['admission_no'] ?? $student->admission_no,
            'application_no' => $data['application_no'] ?? $student->application_no,
            'admission_date' => $admissionDate = Carbon::createFromFormat('d/m/Y', $data['admission_date'])->format('Y-m-d') ?? $student->admission_date,
            'joining_quota' => $data['joining_quota'] ?? $student->joining_quota,
            'first_lang_id' => $data['first_lang_id'] ?? $student->first_lang_id,
            'second_lang_id' => $data['second_lang_id'] ?? $student->second_lang_id,
            'third_lang_id' => $data['third_lang_id'] ?? $student->third_lang_id,
            'achievements' => $data['achievements'] ?? $student->achievements,
            'area_of_interest' => $data['area_of_interest'] ?? $student->area_of_interest,
            'additional_skills' => $data['additional_skills'] ?? $student->additional_skills,
            'image' => $imagePath,
        ]);

        return $student;
    }

    public function Presentstudent($section, $class_id, $studentIds)
    {
        return Student::where('section_id', $section)
            ->where('class_id', $class_id)->whereNotIn('id', $studentIds)->pluck('id')->toArray();
    }

    public function Absentstudent($section, $class_id, $studentIds)
    {
        return Student::where('section_id', $section)
            ->where('class_id', $class_id)->whereIn('id', $studentIds)->pluck('id')->toArray();
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
