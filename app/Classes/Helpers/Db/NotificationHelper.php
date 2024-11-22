<?php

namespace App\Classes\Helpers\Db;

use App\Data\CheckData;
use App\Enums\NotifySourceEnum;
use App\Enums\Orders\StageStatusEnum;
use App\Enums\System\NotificationTypeEnum;
use App\Notifications\Orders\OrderChatNewMessageNotification;
use App\Support\Str;
use Illuminate\Notifications\DatabaseNotification;

class NotificationHelper
{
    public static function message(DatabaseNotification $notification, bool $linkable = true): ?string
    {
        return match ($notification->type) {
            /**
             * Checks.
             */
            NotificationTypeEnum::CHECK_VERIFICATION_ACTION->value => CheckVerifyingActionHelper::getNotificationMessage(CheckData::from([
                'id' => $notification->data['check_id'],
                'status' => $notification->data['check_status'],
                'number' => $notification->data['check_number'],
                'stageId' => $notification->data['stage_id'],
                'orderId' => $notification->data['order_id'],
            ]), $notification->data['check_old_status'], $notification->data['recipient_role'], $linkable),

            /**
             * Orders.
             */
            NotificationTypeEnum::ORDER_CREATED->value => __('notifications.order_new_with_number', [
                'number' => $linkable ? self::link($notification) : '№' . $notification->data['order_id'],
            ]),
            NotificationTypeEnum::ORDER_NEW_MESSAGE->value => match ($notification->data['recipient']) {
                OrderChatNewMessageNotification::DOCTOR => __('notifications.order_with_number_new_chat_message_has_reply', [
                    'number' => $linkable ? self::link($notification) : '№' . $notification->data['order_id'],
                ]),
                OrderChatNewMessageNotification::EMPLOYEE => match ($notification->data['sender']) {
                    OrderChatNewMessageNotification::DOCTOR => __('notifications.order_with_number_new_chat_message_from_doctor', [
                        'number' => $linkable ? self::link($notification) : '№' . $notification->data['order_id'],
                    ]),
                    OrderChatNewMessageNotification::EMPLOYEE => __('notifications.order_with_number_new_chat_message_from_employee', [
                        'number' => $linkable ? self::link($notification) : '№' . $notification->data['order_id'],
                    ]),
                },
            },

            /**
             * Stages.
             */
            NotificationTypeEnum::STAGE_STATUS_CHANGED->value => StageVerifyingActionHelper::getSource(
                $notification->data, $notification->data['stage_old_status'], NotifySourceEnum::NOTIFY->value, getUserRole(), $linkable
            ),
            /*NotificationTypeEnum::STAGE_STATUS_CHANGED->value => match ($notification->data['stage_status']) {
                StageStatusEnum::VERIFICATION->value => __('notifications.order_with_number_for_validation', [
                    'number' => $linkable ? self::link($notification) : '№' . $notification->data['order_id'],
                ]),
                default => __('notifications.order_status_with_number_has_been_changed_to', [
                    'number' => $linkable ? self::link($notification) : '№' . $notification->data['order_id'],
                    'status' => __('stages.status_enums.' . Str::delimiterFromDashToUnderscore($notification->data['stage_status'])),
                ]),
            },*/

            /**
             * Default
             */
            default => null,
        };
    }

    public static function link(DatabaseNotification $notification, $asHtml = true): string
    {
        $link = match ($notification->type) {
            NotificationTypeEnum::ORDER_CREATED->value,
            NotificationTypeEnum::ORDER_NEW_MESSAGE->value,
            NotificationTypeEnum::STAGE_STATUS_CHANGED->value => route('order.edit', ['order' => $notification->data['order_id']]),
            default => null,
        };

        if ($asHtml) {
            $link = match ($notification->type) {
                NotificationTypeEnum::ORDER_CREATED->value,
                NotificationTypeEnum::ORDER_NEW_MESSAGE->value,
                NotificationTypeEnum::STAGE_STATUS_CHANGED->value => '<a href="' . $link . '">№' . $notification->data['order_id'] . '</a>',
                default => null,
            };
        }

        return $link;
    }

    public static function iconBi(DatabaseNotification $notification): string
    {
        return match ($notification->type) {
            NotificationTypeEnum::STAGE_STATUS_CHANGED->value => 'file-text',
            default => null,
        };
    }
}
