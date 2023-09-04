<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecoveryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset your Historiska password',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recovery',
            with: [
                'url' =>
                    config('historiska.mail_link_url')
                    . config('historiska.recovery_base_link')
                    . $this->code
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    protected string $code;
}
