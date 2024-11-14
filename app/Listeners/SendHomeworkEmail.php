<?php

namespace App\Listeners;

use App\Events\HomeworkNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendEmailJob;
use App\Models\Notification;
use App\Models\NotificationLog;

class SendHomeworkEmail
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
    public function handle(HomeworkNotification $event)
    {
        $Data_id = NotificationLog::where('notification_id', $event->notificationId)
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
                SendEmailJob::dispatch($email, 'Home Work', 'Your Childs Home Work Is Ready');
            }
        }
    }
}
