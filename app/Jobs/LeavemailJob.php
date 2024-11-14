<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\LeavsEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LeavemailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $emails; // Explicitly define the type as an array

    public function __construct(array $emails)
    {
        $this->emails = $emails;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $subject = "Student Leaves";
            $email = $this->emails['email'];
            $alt_email = $this->emails['alt_email'];

            Mail::to($email)
                ->cc($alt_email)
                ->send(new LeavsEmail($subject, $this->emails['first_name'], $this->emails['branch_name'], $this->emails['leave_date']));

            Log::channel('job_logs')->info("Email sent successfully to {$email} with CC to {$alt_email}.");
        } catch (\Exception $e) {
            Log::channel('job_logs')->error('Failed to send email: ' . $e->getMessage());
        }
    }
}
