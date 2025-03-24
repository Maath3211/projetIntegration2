<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class reinitialisation extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    /**
     * Create a new message instance.
     */
    public function __construct($token, $locale = null)
    {
        $this->token = $token;
        $this->locale = $locale ?? App::getLocale(); // Use current locale if not specified
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        App::setLocale($this->locale);
        return new Envelope(
            subject: __('emails.reinitialisation_mdp.sujet'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        App::setLocale($this->locale);
        return new Content(
            view: 'Emails.reinitialisation',
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
