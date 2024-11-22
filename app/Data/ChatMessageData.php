<?php

namespace App\Data;

use App\Data\Transformers\CarbonToArrayTransformer;
use App\Models\ChatMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\WithTransformer;

class ChatMessageData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?int             $id,
        public ?int             $chatId,
        public ?int             $userId,
        public ?int             $repliedMessageId,
        public ?string          $type,
        public ?string          $subType,
        public ?string          $message,
        #[WithTransformer(CarbonToArrayTransformer::class)]
        public ?Carbon          $createdAt,
        public ?Collection      $files,
        public ?ChatMessageData $repliedMessage,
    )
    {
    }

    public static function fromModel(ChatMessage $chatMessage): self
    {
        return new self(
            $chatMessage->id,
            $chatMessage->chat_id,
            $chatMessage->user_id,
            $chatMessage->replied_message_id,
            $chatMessage->type,
            $chatMessage->sub_type,
            $chatMessage->message,
            $chatMessage->created_at,
            $chatMessage->whenLoaded(
                'files', fn(ChatMessage $chatMessage) => FileData::collect($chatMessage->whenLoaded('files'))
            ),
            $chatMessage->whenLoaded(
                'repliedMessage', fn(ChatMessage $chatMessage) => ChatMessageData::fromModel($chatMessage->repliedMessage)
            ),
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::from([
            'id' => $request->validated('id'),
            'chatId' => $request->validated('chat_id'),
            'userId' => $request->validated('user_id'),
            'repliedMessageId' => $request->validated('replied_message_id'),
            'type' => $request->validated('type'),
            'subType' => $request->validated('sub_type'),
            'message' => $request->validated('message'),
            'createdAt' => $request->validated('created_at'),
            'files' => $request->file('file')
        ]);
    }
}
