<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Branches;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmissionAnouncement extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='admission_anouncements';
    protected $fillable = [
        'name',
        'branch_id',
        'school_id',
        'academic_year_id',
        'application_fee',
        'start_date',
        'end_date',
        'last_submission_date',
        'class',
        'admission_fees',
        'quota',
        'seats_available',
        'exam_required',
        'status'
    ];

    public function createAdmissionAnnouncement($validatedData)
    {
        $dateService = new DateService();

        $school_id = Branches::where('id', $validatedData['branch_id'])->value('school_id');
        foreach ($validatedData['announcement_data'] as $data) {
            AdmissionAnouncement::create([
                'branch_id' => $validatedData['branch_id'],
                'school_id' => $school_id,
                'academic_year_id' => $validatedData['academic_year_id'],
                'application_fee' => $validatedData['application_fee'],
                'name' => $validatedData['name'],
                'class' => $data['class'],
                'quota' => $data['quota'],
                'seats_available' => $data['seats_available'],
                'exam_required' => $data['exam_required'],
                'admission_fees' => $data['admission_fee'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'last_submission_date' => $validatedData['last_submission_date'],
            ]);
        }
        return true;
        $school_id = Branches::where('id', $data['branch_id'])->value('school_id');
        $admissionAnnouncement = AdmissionAnouncement::create([
            'name' => $data['name'],
            'branch_id' => $data['branch_id'],
            'school_id' => $school_id,
            'academic_year_id' => $data['academic_year_id'],
            'application_fee' => $data['application_fee'],
            'start_date' => $dateService->formatDate($data['start_date']),
            'end_date' => $dateService->formatDate($data['end_date']),
            'last_submission_date' => $dateService->formatDate($data['last_submission_date']),
            'class' => $data['class'],
            'admission_fees' => $data['admission_fees'],
            'quota' => $data['quota'],
            'seats_available' => $data['seats_available'],
            'exam_required' => $data['exam_required']
        ]);

        return $admissionAnnouncement;
    }

    public function updateAnouncement($id, $data)
    {
        $dateService = new DateService();
        $announcement = AdmissionAnouncement::find($id);
        $announcement->update([
            'name' => $data['name'],
            'academic_year_id' => $data['academic_year_id'],
            'application_fee' => $data['application_fee'],
            'start_date' => $dateService->formatDate($data['start_date']),
            'end_date' => $dateService->formatDate($data['end_date']),
            'last_submission_date' => $dateService->formatDate($data['last_submission_date']),
            'class' => $data['class'],
            'admission_fees' => $data['admission_fees'],
            'quota' => $data['quota'],
            'seats_available' => $data['seats_available'],
            'exam_required' => $data['exam_required']
        ]);
        return "Update Successfully";
    }
}

