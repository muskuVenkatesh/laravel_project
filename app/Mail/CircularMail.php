<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CircularMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $recipientType;
    public $recipientData;
    public $circularData;

    /**
     * Create a new message instance.
     */
    public function __construct($recipientType, $recipientData, $circularData)
    {
        $this->recipientType = $recipientType;
        $this->recipientData = $recipientData;
        $this->circularData = $circularData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->generateSubject(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->determineView(),
            with: [
                'recipientType' => $this->recipientType,
                'recipientData' => $this->recipientData,
                'circularData' => $this->circularData
            ],
        );
    }

    /**
     * Determine the email view based on the recipient type.
     */
    private function determineView()
    {
        switch ($this->recipientType) {
            case 'parent':
                return 'Circular';
            case 'staff':
                return 'Circular';
            case 'branch':
                return 'Circular';
            default:
                return 'Circular';
        }
    }

    /**
     * Generate the email subject based on the recipient type.
     */
    private function generateSubject()
    {
        switch ($this->recipientType) {
            case 'parent':
                return 'Circular for Parents';
            case 'student':
                return 'Circular for Students';
            case 'staff':
                return 'Circular for Staff';
            case 'branch':
                return 'Circular for Branch';
            default:
                return 'General Circular';
        }
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
