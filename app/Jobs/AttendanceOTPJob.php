<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\AttendanceOTP;
use Illuminate\Support\Facades\Mail;

class AttendanceOTPJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $studentData;
    public $otp;
    public $userName;
    public $branchName;



    public function __construct($email, $studentData, $otp, $userName, $branchName)
    {
        $this->email = $email;
        $this->studentData = $studentData;
        $this->otp = $otp;
        $this->userName = $userName;
        $this->branchName = $branchName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Prepare the email content
        Mail::to($this->email)->send(new AttendanceOTP($this->studentData, $this->otp, $this->userName, $this->branchName));
    }
}
