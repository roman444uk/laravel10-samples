<?php

namespace App\Events\Chat;

use App\Data\ChatMessageData;
use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageDestroyedEvent implements ShouldBroadcastNow
{
    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        protected ChatMessage $chatMessage,
    )
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];

        foreach ($this->chatMessage->chat->participants as $participant) {
            $channels[] = new PrivateChannel('chats.participants.' . $participant->user_id);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.destroyed';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'message' => ChatMessageData::fromModel($this->chatMessage)
        ];
    }
}
