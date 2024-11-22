<?php

namespace App\Actions\Orders;

use App\Classes\Helpers\Db\CheckVerifyingActionHelper;
use App\Classes\ServicesResponses\OperationResponse;
use App\Data\ChatMessageData;
use App\Enums\Chat\ChatMessageTypeEnum;
use App\Enums\Orders\CheckStatusEnum;
use App\Models\Check;
use App\Services\Db\Chat\ChatMessageService;
use App\Services\Db\Chat\ChatService;

class CheckVerifyingActionChatQuickMessages
{
    public function __construct(
        protected ChatMessageService $chatMessageService,
        protected ChatService $chatService,
    )
    {
    }

    public function handle(Check $check, string $oldStatus): OperationResponse
    {
        $message = CheckVerifyingActionHelper::getChatMessage($check, $oldStatus);
        $subType = CheckVerifyingActionHelper::getChatMessageSubType($check, $oldStatus);

        /**
         * Send to internal chat.
         */
        if (in_array($check->status, [
            CheckStatusEnum::REJECTED_BY_CLINICAL_SPECIALIST->value,
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value,
            CheckStatusEnum::REJECTED_BY_CLINICAL_DIRECTOR->value,
            CheckStatusEnum::VERIFICATION_BY_DOCTOR->value,
            CheckStatusEnum::REJECTED_BY_DOCTOR->value,
            CheckStatusEnum::ACCEPTED->value,
        ])) {
            $this->chatMessageService->store(ChatMessageData::from([
                'chatId' => $this->chatService->getOrderChatInternal($check->stage->order)->id,
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
            in_array($check->status, [
                CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value,
                CheckStatusEnum::VERIFICATION_BY_DOCTOR->value,
                CheckStatusEnum::REJECTED_BY_DOCTOR->value,
                CheckStatusEnum::ACCEPTED->value,
            ]) || (
                $check->status === CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value
                && $oldStatus === CheckStatusEnum::VERIFICATION_BY_DOCTOR->value
            )
        ) {
            $this->chatMessageService->store(ChatMessageData::from([
                'chatId' => $this->chatService->getOrderChatWithDoctor($check->stage->order)->id,
                'userId' => getUserId(),
                'message' => $message,
                'type' => ChatMessageTypeEnum::SYSTEM->value,
                'subType' => $subType,
            ]));
        }

        return successOperationResponse();
    }
}
