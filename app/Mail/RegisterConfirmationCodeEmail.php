<?php

namespace App\Mail;

use App\Traits\EmailTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterConfirmationCodeEmail extends Mailable
{
    use Queueable,
        SerializesModels,
        EmailTrait;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $email,
        public string $code,
    )
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->email,
            subject: $this->prepareSubject(__('emails.register_confirmation_code')),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.register-email-confirmation-code',
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
