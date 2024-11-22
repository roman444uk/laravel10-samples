<?php

namespace App\Notifications\Orders;

use App\Enums\System\NotificationTypeEnum;
use App\Mail\Stage\StageVerifyingActionEmail;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StageVerifyingActionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Stage  $stage,
        protected string $oldStatus,
        protected string $recipientRole,
    )
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(User $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage|Mailable
    {
        return (new StageVerifyingActionEmail($this->stage, $this->oldStatus, $this->recipientRole))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(User $notifiable): array
    {
        return [
            'stage_id' => $this->stage->id,
            'stage_status' => $this->stage->status,
            'stage_old_status' => $this->oldStatus,
            'order_id' => $this->stage->order->id,
            'recipient_role' => $this->recipientRole,
        ];
    }

    /**
     * Get the notification's database type.
     *
     * @return string
     */
    public function databaseType(User $notifiable): string
    {
        return NotificationTypeEnum::STAGE_STATUS_CHANGED->value;
    }
}
