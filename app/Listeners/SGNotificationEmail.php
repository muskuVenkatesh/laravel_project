<?php

namespace App\Listeners;

use App\Events\SGNotificationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendEmailJob;
use App\Models\Notification;
use App\Models\NotificationLog;

class SGNotificationEmail
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
    public function handle(SGNotificationCreated $event): void
    {
        $Data_id = NotificationLog::where('notification_id', $event->notificationId)
        ->whereDate('created_at', $event->notificationdate)
        ->where('type_id', 4)
        ->get();

        $uniqueRecipients = $Data_id->pluck('send_to')->map(function($email) {
            return strtolower(trim($email)); 
        })->unique();

        foreach($uniqueRecipients as $recipient) {
            SendEmailJob::dispatch($recipient, 'Notification', $event->notificationmessage);
        }
    }
}
