<?php

namespace App\Models;

use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmissionEnquiry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'admission_enquiries';

    protected $fillable = [
        'announcement_id', 'application_no', 'application_fee', 'name',
        'father_name', 'contact_no', 'email', 'class_applied', 'dob',
        'second_language', 'course_type', 'payment_mode', 'status',
        'assesment_date'
    ];

    public function createAdmissionEnquery($validatedData)
    {
        $dateService = new DateService();
        return AdmissionEnquiry::create([
            'announcement_id' => $validatedData['announcement_id'],
            'application_no' => $validatedData['application_no'],
            'application_fee' => $validatedData['application_fee'],
            'name' => $validatedData['name'],
            'father_name' => $validatedData['father_name'],
            'contact_no' => $validatedData['contact_no'],
            'email' => $validatedData['email'],
            'class_applied' => $validatedData['class_applied'],
            'dob' => $dateService->formatDate($validatedData['dob']),
            'assesment_date' => $dateService->formatDate($validatedData['assesment_date']),
            'second_language' => $validatedData['second_language'],
            'course_type' => $validatedData['course_type'],
            'payment_mode' => $validatedData['payment_mode'],
            'assesment_date' => $validatedData['assesment_date'],
        ]);
    }

    public function updateAdmissionEnquery($data, $id)
    {
        $dateService = new DateService();
        $admissionenquiry = AdmissionEnquiry::find($id);
        $admissionenquiry->update([
            'announcement_id' => $data['announcement_id'],
            'application_no' => $data['application_no'],
            'application_fee' => $data['application_fee'],
            'name' => $data['name'],
            'father_name' => $data['father_name'],
            'contact_no' => $data['contact_no'],
            'email' => $data['email'],
            'class_applied' => $data['class_applied'],
            'dob' => $dateService->formatDate($data['dob']),
            'assesment_date' => $dateService->formatDate($data['assesment_date']),
            'second_language' => $data['second_language'],
            'course_type' => $data['course_type'],
            'payment_mode' => $data['payment_mode'],
            'assesment_date' => $data['assesment_date'],
        ]);
        return $admissionenquiry;
    }
}
