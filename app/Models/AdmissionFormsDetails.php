<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionFormsDetails extends Model
{
    use HasFactory;

    protected $table = 'admission_forms_details';
    protected $fillable = [
        'admission_id',
        'father_name',
        'middle_name',
        'father_last_name',
        'phone',
        'father_phone',
        'father_email',
        'father_education',
        'father_occupation',
        'annual_income',
        'father_aadhaar_no',
        'father_pan_card',
        'mother_name',
        'mother_phone',
        'mother_email',
        'mother_education',
        'mother_occupation',
        'mother_annual_income',
        'mother_aadhaar_no',
        'mother_pan_card',
        'status',
    ];

    public static function createAadmissionformsdetails($admission_id, $data)
    {
        AdmissionFormsDetails::create([
            'admission_id' => $admission_id,
            'father_name' => $data['father_name'],
            'middle_name' => $data['middle_name'],
            'father_last_name' => $data['father_last_name'],
            'phone' => $data['phone'],
            'father_phone' => $data['father_phone'],
            'father_email' => $data['father_email'],
            'father_education' => $data['father_education'],
            'father_occupation' => $data['father_occupation'],
            'annual_income' => $data['annual_income'],
            'father_aadhaar_no' => $data['father_aadhaar_no'],
            'father_pan_card' => $data['father_pan_card'],
            'mother_name' => $data['mother_name'],
            'mother_phone' => $data['mother_phone'],
            'mother_email' => $data['mother_email'],
            'mother_education' => $data['mother_education'],
            'mother_occupation' => $data['mother_occupation'],
            'mother_annual_income' => $data['mother_annual_income'],
            'mother_aadhaar_no' => $data['mother_aadhaar_no'],
            'mother_pan_card' => $data['mother_pan_card'],
        ]);
    }

    public static function updateAadmissionformsdetails($id, $data)
    {
        $admissionFormDetails = AdmissionFormsDetails::where('admission_id', $id)->first();

        $admissionFormDetails->father_name = $data['father_name'];
        $admissionFormDetails->middle_name = $data['middle_name'];
        $admissionFormDetails->father_last_name = $data['father_last_name'];
        $admissionFormDetails->phone = $data['phone'];
        $admissionFormDetails->father_phone = $data['father_phone'];
        $admissionFormDetails->father_email = $data['father_email'];
        $admissionFormDetails->father_education = $data['father_education'];
        $admissionFormDetails->father_occupation = $data['father_occupation'];
        $admissionFormDetails->annual_income = $data['annual_income'];
        $admissionFormDetails->father_aadhaar_no = $data['father_aadhaar_no'];
        $admissionFormDetails->father_pan_card = $data['father_pan_card'];
        $admissionFormDetails->mother_name = $data['mother_name'];
        $admissionFormDetails->mother_phone = $data['mother_phone'];
        $admissionFormDetails->mother_email = $data['mother_email'];
        $admissionFormDetails->mother_education = $data['mother_education'];
        $admissionFormDetails->mother_occupation = $data['mother_occupation'];
        $admissionFormDetails->mother_annual_income = $data['mother_annual_income'];
        $admissionFormDetails->mother_aadhaar_no = $data['mother_aadhaar_no'];
        $admissionFormDetails->mother_pan_card = $data['mother_pan_card'];
        $admissionFormDetails->save();
    }
}
