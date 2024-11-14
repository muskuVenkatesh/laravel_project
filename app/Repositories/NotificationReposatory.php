<?php

namespace App\Repositories;

use App\Interfaces\NotificationInterface;
use App\Models\Classes;
use App\Models\Student;
use App\Models\NotificationLog;
use Illuminate\Http\Request;
use App\Models\Branches;

class NotificationReposatory implements NotificationInterface
{
    public function __construct(NotificationLog $notificationlog)
    {
        $this->notificationlog = $notificationlog;
    }

    public function getNotificationlogs($notification_type)
    {
        $notification_log = $this->notificationlog->getNotificationlogs($notification_type);
        return $notification_log;
    }
}
