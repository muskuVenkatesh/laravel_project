<?php

namespace App\Repositories;

use App\Interfaces\HomeworkInterface;
use App\Models\Homework;
use App\Models\HomeworkDetails;
use App\Models\HomeworkSpecialInstruction;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Jobs\SendEmailJob;
use DB;
use App\Models\Notification;
use App\Models\NotificationLog;
use App\Events\HomeworkNotification;
use Carbon\Carbon;
class HomeworkRepository implements HomeworkInterface
{
    protected $homework;
    public function __construct(Homework $homework)
    {
        $this->homework = $homework;
    }

    public function storeHomework($data)
    {
        $homework_id = $this->homework->storeHomework($data);

        $homework_details = HomeworkDetails::storeHomeworkDetails($homework_id, $data);
        if($data['special_instruction_student'] != '')
        {
            $specialInstructionData = [
                'special_instruction_student' => $data['special_instruction_student'],
                'special_instruction_message' => $data['special_instruction_message']
            ];

            HomeworkSpecialInstruction::storeHomeworkSpecialInstruction($homework_id, $specialInstructionData);
        }
        if($data['indivedual_student'] != '')
        {
            $indivedualInstructionData = [
                'indivedual_student' => $data['indivedual_student'],
                'indivedual_message' => $data['indivedual_message']
            ];

            HomeworkSpecialInstruction::storeHomeworkSpecialInstruction($homework_id, $indivedualInstructionData);
        }
        $nId = $this->createNotification($data, $homework_id);

        $studentId = Student::where('branch_id', $data['branch_id'])
        ->where('class_id', $data['class_id'])
        ->where('section_id', $data['section_id'])
        ->pluck('id')->toArray();

        $this->createNotificationLog($nId, $data, $studentId);
        if($nId)
        {
            event(new HomeworkNotification(Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d'), $nId));
        }
        return "Homework Create Successsfully.";
    }

    public function getHomeworks($branchId, $classId, $sectionId, $date)
    {
        $sectionIds = trim($sectionId, '[]');
        $sectionIds = explode(', ', $sectionIds);
        $homework = Homework::select('homework.homework_type', 'homework.date','homework.id','homework.branch_id','homework.class_id','homework.section_id')
        ->where('homework.branch_id', $branchId)
        ->where('homework.class_id', $classId)
        ->whereIn('homework.section_id', $sectionIds)
        ->where('homework.date', $date)
        ->get();
        $data= [];
        foreach($homework as $value)
        {
            $data[] = $this->getHomework($value->id);
        }
        return $data;
    }
    public function getHomework($id)
    {
        $homework = Homework::select(
                'branches.branch_name',
                'classes.name as class_name',
                'sections.name as section_name',
                'homework.homework_type',
                'homework.date'
            )
            ->where('homework.id', $id)
            ->join('classes', 'classes.id', '=', 'homework.class_id')
            ->join('sections', 'sections.id', '=', 'homework.section_id')
            ->join('branches', 'branches.id', '=', 'homework.branch_id')
            ->first();

        if (!$homework) {
            return response()->json(['message' => 'No Data Found'], 404);
        }
        $details = HomeworkDetails::select(
                'subjects.name as subject_name',
                'homework_details.homework_data',
                'homework_details.classwork_data',
                'homework_details.books_carry'
            )
            ->where('homework_details.homework_id', $id)
            ->join('subjects', 'subjects.id', '=', 'homework_details.subject_id')
            ->get();
        $response = [
            'branch_name' => $homework->branch_name,
            'class_name' => $homework->class_name,
            'section_name' => $homework->section_name,
            'homework_type' => $homework->homework_type,
            'date' => $homework->date,
            'homework' => $details->map(function ($detail) {
                return [
                    'subject_name' => $detail->subject_name,
                    'homework_data' => $detail->homework_data,
                    'classwork_data' => $detail->classwork_data,
                    'books_carry' => $detail->books_carry,
                ];
            })
        ];
        return $response;
    }

    public function createNotification($data, $notification_data_id)
    {
        $notificationString = is_array($data['notification']) ? implode(',', $data['notification']) : $data['notification'];
        $notificationdata = [
            'type_id' => $notificationString,
            'notification_data_id' => $notification_data_id,
            'notification_type' => $data['notification_type'],
            'template_id' => 1,
            'status' => 1
        ];
        return Notification::createNotification($notificationdata);
    }

    public function createNotificationLog($nId, $data, $studentIds)
    {
        $absentStudentIds = array_filter(
            is_array($studentIds)
            ? $studentIds
            : explode(',', $studentIds)
        );
        foreach ($data['notification'] as $type_id) {
            foreach ($absentStudentIds as $student_id) {
                $emailData = Student::select('parents.alt_email as parent_email', 'parents.alt_phone as parent_phone', 'students.first_name as student_name')
                ->join('parents', 'parents.id', '=', 'students.parent_id')
                ->where('students.id', $student_id)
                ->first();

              $nofication_log_id = NotificationLog::create(['notification_id' => $nId,
                    'type_id' => $type_id,
                    'student_id' => $student_id,
                    'send_to' => $emailData->parent_email,
                    'msg_sender' => "Home Work",
                    'msg_status' => 0
                ]);

                $sendTodata = NotificationLog::find($nofication_log_id->id);
                if($sendTodata->type_id == 4)
                {
                    $sendTodata->send_to = $emailData->parent_email;
                    $sendTodata->save();
                }
                else
                {
                    $sendTodata->send_to = $emailData->parent_phone;
                    $sendTodata->save();
                }
            }
        }
    }
}
