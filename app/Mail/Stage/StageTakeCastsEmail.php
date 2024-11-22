<?php

namespace App\Mail\Stage;

use App\Classes\Helpers\Db\OrderHelper;
use App\Models\Stage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class StageTakeCastsEmail extends Mailable
{
    use Queueable,
        SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Stage $stage,
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
            subject: __('stages.request_received_for_taking_casts_for_order', [
                'number' => OrderHelper::number($this->stage->order),
            ]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.stage.take-casts',
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
