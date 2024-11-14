<?php

namespace App\Jobs;

use App\Models\Student;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Parents;
use App\Models\Branches;
use App\Models\AdmissionForms;
use App\Models\AdmissionFormsDetails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class InsertStudentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $data)
    {
        $this->data = $data;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $admissionData = AdmissionForms::find($this->data);
        $admissionformsdetails = AdmissionFormsDetails::where('admission_id', $this->data)->first();
        //Parents
            $user = User::create([
                'name' => $admissionformsdetails->father_name,
                'email' => $admissionformsdetails->father_email,
                'roleid' => 6,
                'phone' => $admissionformsdetails->phone,
                'status' => 1,
                'password' =>  Hash::make('password'),
            ]);
            $parent = Parents::create([
                'branch_id' => $admissionData->branch_id,
                'user_id' => $user->id,
                'parent_uid' => Str::uuid(),
                'first_name' => $admissionformsdetails->father_name ?? null,
                'last_name' => $admissionData->last_name ?? null,
                'phone' => $admissionformsdetails->phone ?? null,
                'alt_email' => $admissionformsdetails->father_email ?? null,
                'alt_phone' => $admissionformsdetails->father_phone ?? null,
                'education' => $admissionformsdetails->father_education ?? null,
                'occupation' => $admissionformsdetails->father_occupation ?? null,
                'annual_income' => $admissionformsdetails->annual_income ?? null,
                'mother_name' => $admissionformsdetails->mother_name ?? null,
                'mother_phone' => $admissionformsdetails->mother_phone ?? null,
                'mother_email' => $admissionformsdetails->mother_email ?? null,
                'mother_education' => $admissionformsdetails->mother_education ?? null,
                'mother_occupation' => $admissionformsdetails->mother_occupation ?? null,
                'mother_annual_income' => $admissionformsdetails->mother_annual_income ?? null,
                'mother_aadhaar_no' => $admissionformsdetails->mother_aadhaar_no ?? null,
                'mother_pan_card' => $admissionformsdetails->mother_pan_card ?? null,
            ]);
            UserDetails::create([
                'branch_id' => $admissionData->branch_id,
                'user_id' => $user->id,
                'aadhaar_card_no' => $admissionformsdetails->father_aadhaar_no ?? null,
                'pan_card_no' => $admissionformsdetails->father_pan_card ?? null,
            ]);
        //Students
            $student = Student::create([
                'branch_id' => $admissionData->branch_id,
                'academic_year_id' => $admissionData->academic_year_id,
                'parent_id' => $parent->id,
                'roll_no' => $admissionData->roll_no ?? null,
                'first_name' => $admissionData->first_name,
                'middle_name' => $admissionData->middle_name ?? null,
                'last_name' => $admissionData->last_name,
                'fee_book_no' => $admissionData->fee_book_no ?? null,
                'place_of_birth' => $admissionData->place_of_birth ?? null,
                'mother_tongue' => $admissionData->mother_tongue ?? null,
                'physically_challenge' => $admissionData->physically_challenge ?? null,
                'neet_applicable' => $admissionData->neet_applicable ?? null,
                'transport_required' => $admissionData->transport_required ?? null,
                'medium_id' => $admissionData->medium_id ?? null,
                'class_id' => $admissionData->class_id ?? null,
                'section_id' => $admissionData->section_id ?? null,
                'group_id' => $admissionData->group_id ?? null,
                'reg_no' => $admissionData->reg_no ?? null,
                'emis_no' => $admissionData->emis_no ?? null,
                'cse_no' => $admissionData->cse_no ?? null,
                'file_no' => $admissionData->file_no ?? null,
                'admission_no' => $admissionData->admission_no ?? null,
                'application_no' => $admissionData->application_no ?? null,
                'admission_date' => $admissionData->admission_date ?? null,
                'joining_quota' => $admissionData->joining_quota ?? null,
                'first_lang_id' => $admissionData->first_lang_id ?? null,
                'second_lang_id' => $admissionData->second_lang_id ?? null,
                'third_lang_id' => $admissionData->third_lang_id ?? null,
                'achievements' => $admissionData->achievements ?? null,
                'area_of_interest' => $admissionData->area_of_interest ?? null,
                'additional_skills' => $admissionData->additional_skills ?? null,
            ]);
            $branch_code = Branches::getBranchCode($admissionData->branch_id);
            $parentCode = Student::getparentCode($student->id, $branch_code, $parent->id, 3, $admissionData->admission_date);
            $parent_user_id = Parents::updateUsername($parent->id, $parentCode);
            $studentID = Student::getstudentCode($student->id, $branch_code, 3, $admissionData->admission_date );
            $studentupdate = Student::find($student->id);
            $studentupdate->user_id = $studentID;
            $studentupdate->save();
            UserDetails::create([
                'user_id' => $studentID,
                'branch_id' => $admissionData->branch_id,
                'date_of_birth' => $admissionData->date_of_birth?? null,
                'gender' =>  $admissionData->gender ?? null,
                'blood_group' => $admissionData->blood_group ?? null,
                'religion' => $admissionData->religion ?? null,
                'cast' => $admissionData->cast ?? null,
                'mother_tongue' => $admissionData->mother_tongue ?? null,
                'aadhaar_card_no' => $admissionData->addhar_card_no ?? null,
                'pan_card_no' => $admissionData->pan_card_no ?? null,
                'address' => $admissionData->address ?? null,
                'city' => $admissionData->city ?? null,
                'state' => $admissionData->state ?? null,
                'country' => $admissionData->country ?? null,
                'pin' => $admissionData->pin ?? null,
            ]);
    }
}
