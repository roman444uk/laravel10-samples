<?php

namespace App\Mail\Check;

use App\Classes\Helpers\Db\CheckVerifyingActionHelper;
use App\Models\Check;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class CheckVerifyingActionEmail extends Mailable
{
    use Queueable,
        SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Check $check,
        public string $oldStatus,
        public string $recipientRole,
    )
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        View::share('subject', $this->subject);

        return new Envelope(
            subject: CheckVerifyingActionHelper::getEmailSubject($this->check, $this->oldStatus, $this->recipientRole),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.check.verifying-action',
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
