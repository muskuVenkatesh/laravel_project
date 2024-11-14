<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\DateService;

class AdmissionForms extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'admission_forms';
    protected $fillable = [
        'announcement_id',
        'application_type',
        'branch_id',
        'academic_year_id',
        'first_name',
        'middle_name',
        'last_name',
        'fee_book_no',
        'place_of_birth',
        'mother_tongue',
        'physically_challenge',
        'neet_applicable',
        'transport_required',
        'class_id',
        'reg_no',
        'emis_no',
        'cse_no',
        'file_no',
        'admission_no',
        'admission_fee',
        'admission_status',
        'application_no',
        'application_fee',
        'application_status',
        'admission_date',
        'joining_quota',
        'first_lang_id',
        'second_lang_id',
        'third_lang_id',
        'achievements',
        'area_of_interest',
        'additional_skills',
        'previous_school',
        'last_study_course',
        'last_exam_marks',
        'reason_change',
        'reason_gap',
        'date_of_birth',
        'gender',
        'blood_group',
        'religion',
        'cast',
        'nationality',
        'mother_tounge',
        'addhar_card_no',
        'pan_card_no',
        'address',
        'city',
        'state',
        'country',
        'pin',
        'payment_status',
        'extra_curricular_activites',
        'school_enquiry',
        'hostel_required',
        'identification_mark',
        'identification_mark_two',
        'sports',
        'volunteer',
        'quota',
    ];

    public function createAadmissionforms($data)
    {
        $dateService = new DateService();
        $admissionForm = AdmissionForms::create([
            'announcement_id' => $data['announcement_id'],
            'application_type' => $data['application_type'],
            'branch_id' => $data['branch_id'],
            'academic_year_id' => $data['academic_year_id'],
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'fee_book_no' => $data['fee_book_no'],
            'place_of_birth' => $data['place_of_birth'],
            'mother_tongue' => $data['mother_tongue'],
            'physically_challenge' => $data['physically_challenge'],
            'neet_applicable' => $data['neet_applicable'],
            'transport_required' => $data['transport_required'],
            'class_id' => $data['class_id'],
            'reg_no' => $data['reg_no'],
            'emis_no' => $data['emis_no'],
            'cse_no' => $data['cse_no'],
            'file_no' => $data['file_no'],
            'admission_no' => $data['admission_no'],
            'admission_fee' => $data['admission_fee'],
            'admission_status' => $data['admission_status'],
            'application_no' => $data['application_no'],
            'application_fee' => $data['application_fee'],
            'application_status' => $data['application_status'],
            'admission_date' => $dateService->formatDate($data['admission_date']),
            'joining_quota' => $data['joining_quota'] ?? null,
            'first_lang_id' => $data['first_lang_id'] ?? null,
            'second_lang_id' => $data['second_lang_id'] ?? null,
            'third_lang_id' => $data['third_lang_id'] ?? null,
            'area_of_interest' => $data['area_of_interest'] ?? null,
            'additional_skills' => $data['additional_skills'] ?? null,
            'previous_school' => $data['previous_school'] ?? null,
            'last_study_course' => $data['last_study_course'] ?? null,
            'last_exam_marks' => $data['last_exam_marks'] ?? null,
            'reason_change' => $data['reason_change'] ?? null,
            'reason_gap' => $data['reason_gap'] ?? null,
            'date_of_birth' => $dateService->formatDate($data['date_of_birth']),
            'gender' => $data['gender'],
            'blood_group' => $data['blood_group'],
            'religion' => $data['religion'],
            'cast' => $data['cast'],
            'nationality' => $data['nationality'],
            'mother_tongue' => $data['mother_tongue'],
            'addhar_card_no' => $data['addhar_card_no'],
            'pan_card_no' => $data['pan_card_no'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'country' => $data['country'],
            'pin' => $data['pin'],
            'payment_status' => $data['payment_status'] ?? null,
            'extra_curricular_activites' => $data['extra_curricular_activites'] ?? null,
            'school_enquiry' => $data['school_enquiry'] ?? null,
            'hostel_required' => $data['hostel_required'] ?? null,
            'identification_mark' => $data['identification_mark'] ?? null,
            'identification_mark_two' => $data['identification_mark_two'] ?? null,
            'sports' => $data['sports'] ?? null,
            'achievements' => $data['achievements'] ?? null,
            'volunteer' => $data['volunteer'] ?? null,
            'quota' => $data['quota'] ?? null
        ]);
        return $admissionForm->id;
    }

    public function updateAadmissionformbyId($data, $id)
    {
        $admissionForm = AdmissionForms::find($id);

        if($admissionForm->payment_status == 'paid')
        {
            return "False";
        }
        $admissionForm->announcement_id = $data['announcement_id'];
        $admissionForm->application_type = $data['application_type'];
        $admissionForm->branch_id = $data['branch_id'];
        $admissionForm->academic_year_id = $data['academic_year_id'];
        $admissionForm->first_name = $data['first_name'];
        $admissionForm->middle_name = $data['middle_name'];
        $admissionForm->last_name = $data['last_name'];
        $admissionForm->fee_book_no = $data['fee_book_no'];
        $admissionForm->place_of_birth = $data['place_of_birth'];
        $admissionForm->mother_tongue = $data['mother_tongue'];
        $admissionForm->physically_challenge = $data['physically_challenge'];
        $admissionForm->neet_applicable = $data['neet_applicable'];
        $admissionForm->transport_required = $data['transport_required'];
        $admissionForm->class_id = $data['class_id'];
        $admissionForm->reg_no = $data['reg_no'];
        $admissionForm->emis_no = $data['emis_no'];
        $admissionForm->cse_no = $data['cse_no'];
        $admissionForm->file_no = $data['file_no'];
        $admissionForm->admission_no = $data['admission_no'];
        $admissionForm->admission_fee = $data['admission_fee'];
        $admissionForm->admission_status = $data['admission_status'];
        $admissionForm->application_no = $data['application_no'];
        $admissionForm->application_fee = $data['application_fee'];
        $admissionForm->application_status = $data['application_status'];
        $admissionForm->admission_date = $dateService->formatDate($data['admission_date']);
        $admissionForm->joining_quota = $data['joining_quota'] ?? null;
        $admissionForm->first_lang_id = $data['first_lang_id'] ?? null;
        $admissionForm->second_lang_id = $data['second_lang_id'] ?? null;
        $admissionForm->third_lang_id = $data['third_lang_id'] ?? null;
        $admissionForm->area_of_interest = $data['area_of_interest'] ?? null;
        $admissionForm->additional_skills = $data['additional_skills'] ?? null;
        $admissionForm->previous_school = $data['previous_school'] ?? null;
        $admissionForm->last_study_course = $data['last_study_course'] ?? null;
        $admissionForm->last_exam_marks = $data['last_exam_marks'] ?? null;
        $admissionForm->reason_change = $data['reason_change'] ?? null;
        $admissionForm->reason_gap = $data['reason_gap'] ?? null;
        $admissionForm->date_of_birth = $dateService->formatDate($data['date_of_birth']);
        $admissionForm->gender = $data['gender'];
        $admissionForm->blood_group = $data['blood_group'];
        $admissionForm->religion = $data['religion'];
        $admissionForm->cast = $data['cast'];
        $admissionForm->nationality = $data['nationality'];
        $admissionForm->mother_tongue = $data['mother_tongue'];
        $admissionForm->addhar_card_no = $data['addhar_card_no'];
        $admissionForm->pan_card_no = $data['pan_card_no'];
        $admissionForm->address = $data['address'];
        $admissionForm->city = $data['city'];
        $admissionForm->state = $data['state'];
        $admissionForm->country = $data['country'];
        $admissionForm->pin = $data['pin'];
        $admissionForm->payment_status = $data['payment_status'] ?? null;
        $admissionForm->extra_curricular_activites = $data['extra_curricular_activites'] ?? null;
        $admissionForm->school_enquiry = $data['school_enquiry'] ?? null;
        $admissionForm->hostel_required = $data['hostel_required'] ?? null;
        $admissionForm->identification_mark = $data['identification_mark'] ?? null;
        $admissionForm->identification_mark_two = $data['identification_mark_two'] ?? null;
        $admissionForm->sports = $data['sports'] ?? null;
        $admissionForm->achievements = $data['achievements'] ?? null;
        $admissionForm->volunteer = $data['volunteer'] ?? null;
        $admissionForm->quota = $data['quota'] ?? null;

        $admissionForm->save();
        return $admissionForm->id;
    }
}
