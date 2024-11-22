<?php

namespace App\Events\Chat;

use App\Data\ChatData;
use App\Models\Chat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatCreatedEvent implements ShouldBroadcastNow
{
    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        protected Chat $chat,
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

        foreach ($this->chat->participants as $participant) {
            $channels[] = new PrivateChannel('chats.participants.' . $participant->user_id);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'chat.created';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $this->chat->loadMessages()->loadParticipants();

        return [
            'chat' => ChatData::fromModel($this->chat)
        ];
    }
}
