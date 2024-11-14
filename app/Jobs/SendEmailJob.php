<?php

namespace App\Jobs;

use App\Mail\SendEmail;
use App\Models\Student;
use App\Models\NotificationLog;
use App\Models\Parents;
use App\Models\Notification;
use App\Models\HomeworkDetails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notificationData;
    protected $subject;
    protected $content;

    public function __construct($notificationData, $subject, $content)
    {
        $this->notificationData = $notificationData;
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
           //echo $this->notificationData;
            try {
            Mail::to($this->notificationData)->send(new SendEmail($this->subject, $this->content));
            NotificationLog::where('send_to', $this->notificationData)
            ->update(['msg_status' => 1]);
                return "Email sent successfully.";
            } catch (\Exception $e) {

                return "Failed to send email: " . $e->getMessage();
            }
    }
}
