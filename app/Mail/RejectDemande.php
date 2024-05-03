<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RejectDemande extends Mailable
{
    use Queueable, SerializesModels;


    public $student;
    public $user;
    public $proposition;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $student, $proposition)
    {
        $this->user = $user;
        $this->student = $student;
        $this->proposition = $proposition;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reject Demande',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.rejectDemande',
            with:[
                'student' => $this->student,
                'user' => $this->user,
                'proposition' => $this->proposition,
            ]
        );
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
