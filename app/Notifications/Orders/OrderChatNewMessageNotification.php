<?php

namespace App\Notifications\Orders;

use App\Enums\System\NotificationTypeEnum;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderChatNewMessageNotification extends Notification
{
    use Queueable;

    const DOCTOR = 'doctor';
    const EMPLOYEE = 'employee';

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected User        $user,
        protected ChatMessage $chatMessage,
        protected string      $sender,
        protected string      $recipient,
    )
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'order_id' => $this->chatMessage->chat->context_id,
            'chat_id' => $this->chatMessage->chat_id,
            'chat_message_id' => $this->chatMessage->id,
            'sender' => $this->sender,
            'recipient' => $this->recipient,
        ];
    }

    /**
     * Get the notification's database type.
     *
     * @return string
     */
    public function databaseType(object $notifiable): string
    {
        return NotificationTypeEnum::ORDER_NEW_MESSAGE->value;
    }
}
