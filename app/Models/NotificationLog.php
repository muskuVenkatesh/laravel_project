<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'notification_id',
        'type_id',
        'student_id',
        'send_to',
        'msg_sender',
        'msg_status',
    ];

    public static function updateTyped($noficationID, $absentStudentIds)
    {
        foreach($absentStudentIds as $absentStudentId)
        {
            $notificationLog = NotificationLog::find($noficationID);
            $notificationLog->student_id = $absentStudentIds;
            $notificationLog->save();
        }
    }

    public function getNotificationlogs($notification_type)
    {
        return NotificationLog::where('notification_logs.type_id', $notification_type)
        ->join('students', 'students.id', '=', 'notification_logs.student_id')
        ->join('notifications', 'notifications.id', '=', 'notification_logs.notification_id')
        ->select('students.first_name as student_name', 'notifications.notification_type', 'notification_logs.*')
        ->get();
    }
}
