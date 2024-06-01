<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMemoire extends Mailable
{
    use Queueable, SerializesModels;

    public $jury;
    public $linkToMemoire;

    public $pfe;
    /**
     * Create a new message instance.
     */
    public function __construct($jury, $linkToMemoire, $pfe)
    {
        $this->jury = $jury;
        $this->linkToMemoire = $linkToMemoire;
        $this->pfe = $pfe;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Memoire',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.sendmemoire',
            with:['jury'=>$this->jury, 'linkToMemoire'=>$this->linkToMemoire, 'pfe'=>$this->pfe]
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
