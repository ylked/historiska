<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Activation de votre compte Historiska',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.activate',
            with: [
                'code' => $this->code,
                'link' =>
                    config('historiska.mail_link_url')
                    . config('historiska.activation_base_link')
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    protected string $code;
}
