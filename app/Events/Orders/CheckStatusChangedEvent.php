<?php

namespace App\Events\Orders;

use App\Models\Check;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckStatusChangedEvent
{
    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Check $check,
        public string $oldStatus,
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
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
