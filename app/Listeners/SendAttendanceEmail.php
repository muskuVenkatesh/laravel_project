<?php

namespace App\Listeners;

use App\Events\NotificationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendEmailJob;
use App\Models\Notification;
use App\Models\NotificationLog;

class SendAttendanceEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationCreated $event): void
    {
        $Data_id = NotificationLog::whereIn('notification_id', $event->notificationId)
        ->whereDate('created_at', $event->notificationdate)
        ->get();
        $filteredData = $Data_id->filter(function ($item) {
            return $item->type_id == 4;
        });
        $sendToValues = $filteredData->pluck('send_to')->map(function ($email) {
            return trim($email);
        })->unique();

        if ($Data_id->contains('type_id', 4)) {
            foreach ($sendToValues as $email) {
                SendEmailJob::dispatch($email, 'Absent','Your Child Is Absent');
            }
        }
    }
}
