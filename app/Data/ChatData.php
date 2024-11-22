<?php

namespace App\Data;

use App\Classes\Helpers\Db\ChatHelper;
use App\Data\Transformers\CarbonToArrayTransformer;
use App\Models\Chat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\WithTransformer;

class ChatData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?int             $id,
        public string           $context,
        public int              $contextId,
        public ?string          $type,
        public ?int             $notViewedMessagesCount,
        public ?string          $title,
        #[WithTransformer(CarbonToArrayTransformer::class)]
        public ?Carbon          $createdAt,
        public ?ChatMessageData $lastMessage,
        public ?Collection      $messages,
        public ?Collection      $participants,
    )
    {
    }

    public static function fromModel(Chat $chat): self
    {
        return new self(
            $chat->id,
            $chat->context,
            $chat->context_id,
            $chat->type,
            $chat->not_viewed_messages_count,
            ChatHelper::title($chat),
            $chat->created_at,
            $chat->whenLoaded('lastMessage', fn(Chat $chat) => ChatMessageData::fromModel($chat->lastMessage)),
            $chat->whenLoaded('messages', fn(Chat $chat) => ChatMessageData::collect($chat->messages)),
            $chat->whenLoaded('participants', fn(Chat $chat) => ChatParticipantData::collect($chat->participants)),
        );
    }
}
