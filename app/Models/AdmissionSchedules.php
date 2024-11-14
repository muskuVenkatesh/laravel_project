<?php

namespace App\Models;

use Carbon\Carbon;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmissionSchedules extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'admission_schedules';
    protected $fillable = [
        'enquiry_id',
        'venue',
        'interview_date',
        'comments',
        'schedule_status',
    ];

    public function createAdmissionSchedules($data)
    {
        $dateService = new DateService();
        foreach($data['enquiry_id'] as $enquiry_id)
        {
            $admissionSchedule = AdmissionSchedules::create([
                'enquiry_id' => $enquiry_id,
                'venue' => $data['venue'] ?? null,
                'interview_date' => !empty($data['interview_date']) ? $dateService->formatDate($data['interview_date']) : null,
                'comments' => $data['comments'] ?? null,
                'schedule_status' => $data['schedule_status'] ?? null,
            ]);
        }
        return $admissionSchedule;
    }

    public function updateSchedules($data)
    {
        $dateService = new DateService();
        foreach($data['enquiry_id'] as $enquiry_id)
        {
            $schedule = AdmissionSchedules::where('enquiry_id', $enquiry_id);
            $schedule->update([
                'venue' => $data['venue'] ?? null,
                'interview_date' => !empty($data['interview_date']) ? $dateService->formatDate($data['interview_date']) : null,
                'comments' => $data['comments'] ?? null,
                'schedule_status' => $data['schedule_status'] ?? null,
            ]);
        }
        return "Schedule updated successfully";
    }
}
