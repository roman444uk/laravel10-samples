<?php

namespace App\Actions\Orders;

use App\Classes\Helpers\Db\StageVerifyingActionHelper;
use App\Classes\ServicesResponses\OperationResponse;
use App\Data\ChatMessageData;
use App\Enums\Chat\ChatMessageTypeEnum;
use App\Enums\NotifySourceEnum;
use App\Enums\Orders\CheckStatusEnum;
use App\Enums\Orders\StageStatusEnum;
use App\Models\Stage;
use App\Services\Db\Chat\ChatMessageService;
use App\Services\Db\Chat\ChatService;

class StageVerifyingActionChatQuickMessages
{
    public function __construct(
        protected ChatMessageService $chatMessageService,
        protected ChatService        $chatService,
    )
    {
    }

    public function handle(Stage $stage, string $oldStatus): OperationResponse
    {
        $message = StageVerifyingActionHelper::getSource($stage, $oldStatus, NotifySourceEnum::CHAT->value);
        $subType = StageVerifyingActionHelper::getSource($stage, $oldStatus, NotifySourceEnum::CHAT_SUB_TYPE->value);

        /**
         * Send to internal chat.
         */
        if (
            in_array($stage->status, [
                StageStatusEnum::VERIFICATION->value,
                StageStatusEnum::PREPARATION->value,
                StageStatusEnum::PAYMENT_BILL_AFTER_REJECTION->value,
                StageStatusEnum::PAYMENT_AWAITING->value,
                StageStatusEnum::PAYMENT_AWAITING_AFTER_REJECTION->value,
                StageStatusEnum::PRODUCTION_OPTIONS->value,
                StageStatusEnum::PRODUCTION_PREPARATION->value,
                StageStatusEnum::PRODUCTION_RELEASE->value,
                StageStatusEnum::PRODUCTION_PACKAGING->value,
                StageStatusEnum::PRODUCTION_CONTROL->value,
                StageStatusEnum::DELIVERY_PREPARATION->value,
                StageStatusEnum::DELIVERY->value,
                StageStatusEnum::DELIVERED->value,
                StageStatusEnum::TREATMENT->value,
                StageStatusEnum::COMPLETED->value,
            ]) || (
                $stage->status === StageStatusEnum::DRAFT->value && $oldStatus === StageStatusEnum::VERIFICATION->value
            ) || (
                $stage->status === StageStatusEnum::MODELING->value && $oldStatus === StageStatusEnum::PREPARATION->value
            )
        ) {
            $this->chatMessageService->store(ChatMessageData::from([
                'chatId' => $this->chatService->getOrderChatInternal($stage->order)->id,
                'userId' => getUserId(),
                'message' => $message,
                'type' => ChatMessageTypeEnum::SYSTEM->value,
                'subType' => $subType,
            ]));
        }

        /**
         * Send to common chat.
         */
        if (
            in_array($stage->status, [
                StageStatusEnum::PAYMENT_BILL_AFTER_REJECTION->value,
                StageStatusEnum::PAYMENT_AWAITING->value,
                StageStatusEnum::PAYMENT_AWAITING_AFTER_REJECTION->value,
                StageStatusEnum::PRODUCTION_OPTIONS->value,
                StageStatusEnum::DELIVERY->value,
                StageStatusEnum::DELIVERED->value,
            ]) || (
                $stage->status === CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value
                && $oldStatus === CheckStatusEnum::VERIFICATION_BY_DOCTOR->value
            )
        ) {
            $this->chatMessageService->store(ChatMessageData::from([
                'chatId' => $this->chatService->getOrderChatWithDoctor($stage->order)->id,
                'userId' => getUserId(),
                'message' => $message,
                'type' => ChatMessageTypeEnum::SYSTEM->value,
                'subType' => $subType,
            ]));
        }

        return successOperationResponse();
    }
}
