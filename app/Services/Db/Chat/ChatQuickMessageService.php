<?php

namespace App\Services\Db\Chat;

use App\Classes\ServicesResponses\OperationResponse;
use App\Data\ChatMessageData;
use App\Enums\Chat\ChatMessageSubTypeEnum;
use App\Enums\Chat\ChatMessageTypeEnum;
use App\Models\Order;
use App\Models\Production;
use App\Models\Stage;
use App\Support\Utilities\DateTime;

class ChatQuickMessageService extends ChatMessageService
{
    /**
     * Send message with problem description.
     */
    public function haveProblem(Order $order, string $message): OperationResponse
    {
        $this->store(ChatMessageData::from([
            'chatId' => $this->chatService->getOrderChatWithDoctor($order)->id,
            'userId' => getUserId(),
            'message' => $message,
            'subType' => ChatMessageSubTypeEnum::DOCTOR_HAVE_PROBLEM->value,
        ]));

        return successOperationResponse();
    }

    public function productionNeedMoreSteps(Production $production): OperationResponse
    {
        $this->store(ChatMessageData::from([
            'chatId' => $this->chatService->getOrderChatWithDoctor($production->check->stage->order)->id,
            'userId' => getUserId(),
            'message' => __('productions.need_production_more_steps'),
            'subType' => ChatMessageSubTypeEnum::DOCTOR_PRODUCTION_NEED_MORE_STEPS->value,
        ]));

        $this->store(ChatMessageData::from([
            'chatId' => $this->chatService->getOrderChatInternal($production->check->stage->order)->id,
            'userId' => getUserId(),
            'message' => __('productions.need_production_more_steps'),
            'type' => ChatMessageTypeEnum::SYSTEM->value,
            'subType' => ChatMessageSubTypeEnum::DOCTOR_PRODUCTION_NEED_MORE_STEPS->value,
        ]));

        return successOperationResponse();
    }

    /**
     * Send require new production message.
     */
    public function productionRequireNext(Stage $stage): OperationResponse
    {
        $this->store(ChatMessageData::from([
            'chatId' => $this->chatService->getOrderChatWithDoctor($stage->order)->id,
            'userId' => getUserId(),
            'message' => __('productions.required_new_batch'),
            'subType' => ChatMessageSubTypeEnum::DOCTOR_PRODUCTION_REQUIRE_NEXT->value,
        ]));

        return successOperationResponse();
    }

    /**
     * Send take casts requirement message.
     */
    public function takeCasts(Stage $stage): OperationResponse
    {
        $orderChatWithDoctor = $this->chatService->getOrderChatWithDoctor($stage->order);

        if ($orderChatWithDoctor) {
            $this->store(ChatMessageData::from([
                'chatId' => $orderChatWithDoctor->id,
                'userId' => getUserId(),
                'message' => __('stages.should_take_casts_by_address_on_time', [
                    'address' => $stage->takeCastsAddress->address,
                    'date' => DateTime::renderDate($stage->take_casts_date),
                    'time' => DateTime::renderTime($stage->take_casts_time),
                ]),
                'subType' => ChatMessageSubTypeEnum::DOCTOR_TAKE_CASTS->value,
            ]));
        }

        return successOperationResponse();
    }
}
