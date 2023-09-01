<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountDeletionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Account Deletion',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-deletion',
            with: ['link' => config('historiska.mail_link_url') .
                config('historiska.deletion_base_link') . $this->code]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    protected string $code;
}
