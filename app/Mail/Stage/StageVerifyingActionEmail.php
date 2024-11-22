<?php

namespace App\Mail\Stage;

use App\Classes\Helpers\Db\StageVerifyingActionHelper;
use App\Enums\NotifySourceEnum;
use App\Models\Stage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class StageVerifyingActionEmail extends Mailable
{
    use Queueable,
        SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Stage  $stage,
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
            subject: StageVerifyingActionHelper::getSource(
                $this->stage, $this->oldStatus, NotifySourceEnum::EMAIL_SUBJECT->value, $this->recipientRole
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.stage.verifying-action',
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
