<?php

namespace App\Notifications\Orders;

use App\Enums\System\NotificationTypeEnum;
use App\Mail\Check\CheckVerifyingActionEmail;
use App\Models\Check;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CheckVerifyingActionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Check $check,
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
        return (new CheckVerifyingActionEmail($this->check, $this->oldStatus, $this->recipientRole))
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
            'check_id' => $this->check->id,
            'check_number' => $this->check->number,
            'check_status' => $this->check->status,
            'check_old_status' => $this->oldStatus,
            'stage_id' => $this->check->stage->id,
            'order_id' => $this->check->stage->order->id,
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
        return NotificationTypeEnum::CHECK_VERIFICATION_ACTION->value;
    }
}
