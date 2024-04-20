<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendRecomandation extends Mailable
{
    use Queueable, SerializesModels;


    public $user;

    public $subjects;
    /**
     * Create a new message instance.
     */
    public function __construct($user, $subjects)
    {
        $this->user = $user;
        $this->subjects = $subjects;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reminder: Final Day for PFE Submission Tomorrow!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.recomndationSubject',
            with:[
                'user' => $this->user,
                'subjects' => $this->subjects,
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
