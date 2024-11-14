<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AttendanceOTP extends Mailable
{
    use Queueable, SerializesModels;

    public $students;
    public $otp;
    public $userName;
    public $branchName;

    /**
     * Create a new message instance.
     */
    public function __construct($students, $otp, $userName, $branchName)
    {
        $this->students = $students;
        $this->otp = $otp;
        $this->userName = $userName;
        $this->branchName = $branchName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('EmailTemplates.subject_createemail')
                    ->subject('Attendance OTP')
                    ->with([
                        'students' => $this->students,
                        'otp' => $this->otp,
                        'userName' => $this->userName,
                        'branchName' => $this->branchName
                    ]);
        return $this->view('')
                    ->subject('Attendance OTP');
    }
}

