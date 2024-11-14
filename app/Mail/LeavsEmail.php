<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeavsEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $student_name;
    public $branchName;
    public $leave_date;

    public function __construct($subject, $student_name, $branchName, $leave_date)
    {
        $this->subject = $subject;
        $this->student_name = $student_name;
        $this->branchName = $branchName;
        $this->leave_date = $leave_date;
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->view('EmailTemplates.leaves_email')
                    ->subject( $this->subject )
                    ->with([
                        'student_name' => $this->student_name,
                        'branchName' => $this->branchName,
                        'leave_date' => $this->leave_date
                    ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
