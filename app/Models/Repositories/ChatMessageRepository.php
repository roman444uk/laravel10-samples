<?php

namespace App\Models\Repositories;

use App\Data\ChatMessageData;
use App\Models\ChatMessage;

class ChatMessageRepository
{
    public function create(ChatMessageData $chatMessageData): ?ChatMessage
    {
        return ChatMessage::create(array_merge($chatMessageData->toArray(), [
            'chat_id' => $chatMessageData->chatId,
            'replied_message_id' => $chatMessageData->repliedMessageId,
            'user_id' => $chatMessageData->userId ?? getUserId(),
            'type' => $chatMessageData->type,
            'sub_type' => $chatMessageData->subType,
            'message' => $chatMessageData->message,
        ]));
    }

    public function update(ChatMessage $chatMessage, ChatMessageData $chatMessageData): bool
    {
        $chatMessage->fill([
            'replied_message_id' => $chatMessageData->repliedMessageId ?? $chatMessage->replied_message_id,
            'message' => $chatMessageData->message ?? $chatMessage->message,
        ]);

        return $chatMessage->save();
    }
}
