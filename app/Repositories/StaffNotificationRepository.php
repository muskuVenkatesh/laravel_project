<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Models\NotificationLog;
use App\Models\Staff;
use App\Events\SGNotificationCreated;
use App\Interfaces\StaffNotificationInterface;


class StaffNotificationRepository implements StaffNotificationInterface
{
    
    public function storeNotification($data)
    {
        $notification = Notification::create([
            'type_id' => is_array($data['type_id']) ? implode(',', $data['type_id']) : $data['type_id'],
            'notification_type' => $data['notification_type'],
            'notification_data_id' => $data['notification_data_id'],
            'tample_id' => 1,
            'status' => 1, 
        ]);
        $nIds = $notification->id;
        $type_id = Notification::find($nIds)->type_id;
        $type_id_array = is_string($type_id) ? explode(',', $type_id) : (array) $type_id;
        foreach($type_id_array as $type_ids)
        {
            $this->storeNotificationlogs($nIds, $type_ids, $data);
        }
        if (in_array(4, $data['type_id'])) {
            event(new SGNotificationCreated(now()->format('Y-m-d'), $nIds, $data['notification_message']));
        }
    }

    public function storeNotificationlogs($notificationId, $type_id, $data)
    {
        $send_t0 = 0;
        foreach($data['staff_id'] as $staff_id)
        {
            if($type_id == 4)
            {
                $send_to = Staff::where('id', $staff_id)->value('email');
            }
            else
            {
                $send_to = Staff::where('staff.id', $staff_id)
                ->join('users', 'users.id', '=', 'staff.user_id')->value('users.phone');
            }
            NotificationLog::create([
                'notification_id' => $notificationId,
                'type_id' => $type_id,
                'student_id' => $staff_id,
                'send_to' => $send_to,
                'msg_sender' => $data['notification_message'],
                'msg_status' => 0,
            ]);
        }
    }
}
