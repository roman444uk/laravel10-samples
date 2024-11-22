<?php

namespace App\Services\Db\Chat;

use App\Classes\Helpers\Db\OrderHelper;
use App\Classes\ServicesResponses\OperationResponse;
use App\Data\ChatData;
use App\Enums\Chat\ChatContextEnum;
use App\Enums\Chat\ChatTypeEnum;
use App\Models\Chat;
use App\Models\Order;
use App\Models\Repositories\ChatRepository;

class ChatService
{
    public function __construct(
        protected ChatRepository $chatRepository,
    )
    {
    }

    /**
     * Returns chat with doctor based on order (finds existed one or creates if one doesn't exist yet).
     */
    public function getOrderChatWithDoctor(Order $order): ?Chat
    {
        if (!$order->chatWithDoctor && !OrderHelper::canChatBeCreated($order)) {
            return null;
        }

        $chat = $order->chatWithDoctor ?? $this->chatRepository->firstOrCreate(ChatData::from([
            'context' => ChatContextEnum::ORDER->value,
            'contextId' => $order->id,
            'type' => ChatTypeEnum::WITH_DOCTOR->value,
        ]));

        app(ChatSyncService::class)->syncOrderChatWithDoctorParticipants($chat, $order);

        $chat->loadParticipants()->loadMessages();

        $this->chatRepository->applyNotViewedMessagesCount($chat, $order->doctor->user_id);

        return $chat;
    }

    /**
     * Returns internal chat based on order (finds existed one or creates if one doesn't exist yet).
     */
    public function getOrderChatInternal(Order $order): ?Chat
    {
        if (!$order->chatInternal && !OrderHelper::canChatBeCreated($order)) {
            return null;
        }

        $chat = $order->chatInternal ?? $this->chatRepository->firstOrCreate(ChatData::from([
            'context' => ChatContextEnum::ORDER->value,
            'contextId' => $order->id,
            'type' => ChatTypeEnum::INTERNAL->value,
        ]));

        app(ChatSyncService::class)->syncOrderChatInternalParticipants($chat, $order);

        $chat->loadParticipants()->loadMessages();

        $this->chatRepository->applyNotViewedMessagesCount($chat, $order->doctor->user_id);

        return $chat;
    }

    /**
     * Marks all chat messages as viewed.
     */
    public function markAsViewed(Chat $chat, $userId): OperationResponse
    {
        $this->chatRepository->markAsViewed($chat, $userId);

        return successOperationResponse();
    }
}
