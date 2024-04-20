<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FindBinomForYou extends Mailable
{
    use Queueable, SerializesModels;

    public $sender;
    public $reciver;
    /**
     * Create a new message instance.
     */
    public function __construct($sender,$reciver)
    {
        $this->sender = $sender;
        $this->reciver = $reciver;
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
            view: 'mails.findBinom',
            with : [
                'sender'=> $this->sender,
                'reciver'=> $this->reciver
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
