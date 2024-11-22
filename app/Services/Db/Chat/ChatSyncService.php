<?php

namespace App\Services\Db\Chat;

use App\Classes\Helpers\Db\OrderHelper;
use App\Classes\Helpers\Db\StageHelper;
use App\Enums\Chat\ChatParticipantStatusEnum;
use App\Enums\Chat\ChatTypeEnum;
use App\Enums\Orders\StageStatusEnum;
use App\Enums\Users\UserRoleEnum;
use App\Events\Chat\ChatCreatedEvent;
use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\Order;
use App\Models\Repositories\ChatRepository;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ChatSyncService
{
    public function __construct(
        protected ChatRepository            $chatRepository,
    )
    {
    }

    /**
     * Synchronize order's chat internal participants.
     */
    public function syncOrderChatInternalParticipants(Chat $chat, Order $order): void
    {
        if ($chat->type === ChatTypeEnum::INTERNAL->value && OrderHelper::canChatBeCreated($order)) {
            $stageActual = $order->stageActual;

            if ($stageActual->status !== StageStatusEnum::DRAFT->value) {
                $participantUserIds = array_filter([
                    User::firstWhere('role', UserRoleEnum::CLINICAL_DIRECTOR->value)?->id,
                    $order->doctor->client_manager_id,
                    $stageActual?->clinical_specialist_id,
                    $stageActual?->modeler_3d_id,
                    $stageActual?->technician_digitizer_id,
                    $stageActual?->technician_production_id,
                    $stageActual?->logistic_manager_id,
                ]);

                if ($order->stageActual && StageHelper::hasReachedStatus($order->stageActual, StageStatusEnum::PRODUCTION_PREPARATION->value)) {
                    $participantUserIds[] = User::isProductionManager()->first()?->id;
                }

                $this->syncParticipants($chat, $participantUserIds);
            }
        }
    }

    /**
     * Synchronize order's chat with doctor participants.
     */
    public function syncOrderChatWithDoctorParticipants(Chat $chat, Order $order): void
    {
        if ($chat->type === ChatTypeEnum::WITH_DOCTOR->value && OrderHelper::canChatBeCreated($order)) {
            $participantUserIds = array_filter([
                $order->doctor->user_id,
                User::firstWhere('role', UserRoleEnum::CLINICAL_DIRECTOR->value)?->id,
                $order->doctor->client_manager_id,
                $order->stageActual->clinical_specialist_id,
            ]);

            $this->syncParticipants($chat, $participantUserIds);
        }
    }

    /**
     * Synchronize chat participants.
     */
    protected function syncParticipants(Chat $chat, array|int $participantUserIds = null): void
    {
        $fireCreatedEvent = false;

        foreach ($participantUserIds as $participantUserId) {
            if ($chat->participants()->where('user_id', $participantUserId)->count() === 0 && $participantUserId) {
                $this->chatRepository->createParticipant($chat, $participantUserId);
                $fireCreatedEvent = true;
            }
        }

        // Enable chats for users that are active employees
        ChatParticipant::where('chat_id', $chat->id)->whereIn('user_id', $participantUserIds)->update([
            'status' => ChatParticipantStatusEnum::ACTIVE->value
        ]);

        // Disable chats for users that are not employees anymore
        ChatParticipant::where('chat_id', $chat->id)->whereNotIn('user_id', $participantUserIds)->update([
            'status' => ChatParticipantStatusEnum::DISABLED->value
        ]);

        if ($fireCreatedEvent) {
            ChatCreatedEvent::dispatch($chat);
        }
    }

    /**
     * Synchronize chats related to particular model.
     */
    public function syncModel(Model $model): void
    {
        match ($model::class) {
            Stage::class => $this->syncStage($model),
        };
    }

    /**
     * Synchronize chats related to stage's order.
     */
    public function syncStage(Stage $stage): void
    {
        $order = Order::firstWhere('id', $stage->order_id);

        if ($chatInternal = app(ChatService::class)->getOrderChatInternal($order)) {
            $this->syncOrderChatInternalParticipants($chatInternal, $order);
        }

        if ($chatWithDoctor = app(ChatService::class)->getOrderChatWithDoctor($order)) {
            $this->syncOrderChatWithDoctorParticipants($chatWithDoctor, $order);
        }
    }
}
