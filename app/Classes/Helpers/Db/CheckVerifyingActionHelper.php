<?php

namespace App\Classes\Helpers\Db;

use App\Data\CheckData;
use App\Enums\Chat\ChatMessageSubTypeEnum;
use App\Enums\Orders\CheckStatusEnum;
use App\Models\Check;

class CheckVerifyingActionHelper
{
    /**
     * Database notification message.
     */
    public static function getNotificationMessage(
        Check|CheckData $check, string $oldStatus, string $recipientRole, bool $linkable = true
    ): string
    {
        $orderId = $check instanceof Check ? $check->stage->order_id : $check->orderId;

        $params = [
            'number' => $linkable ? CheckHelper::getLink($check) : CheckHelper::number($check),
            'orderNumber' => $linkable ? OrderHelper::getLink($orderId) : OrderHelper::number($orderId),
        ];

        return match ($check->status) {
            /** By 3D modeler */
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value => __('checks.3d_check_accepted.by_modeler_3d.notification', $params),

            /** By clinical specialist */
            CheckStatusEnum::REJECTED_BY_CLINICAL_SPECIALIST->value => __('checks.3d_check_rejected.by_clinical_specialist.notification', $params),
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value => match ($oldStatus) {
                CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value => __('checks.3d_check_accepted.by_clinical_specialist.notification', $params),
                /** Recalled by clinical director */
                CheckStatusEnum::VERIFICATION_BY_DOCTOR->value => __('checks.3d_check_recalled.by_clinical_director.notification', $params),
            },

            /** By clinical director */
            CheckStatusEnum::REJECTED_BY_CLINICAL_DIRECTOR->value => __('checks.3d_check_rejected.by_clinical_director.notification', $params),
            CheckStatusEnum::VERIFICATION_BY_DOCTOR->value => __('checks.3d_check_accepted.by_clinical_director.notification', $params),

            /** By doctor */
            CheckStatusEnum::REJECTED_BY_DOCTOR->value => __('checks.3d_check_rejected.by_doctor.notification', $params),
            CheckStatusEnum::ACCEPTED->value => __('checks.3d_check_accepted.by_doctor.notification', $params),
        };
    }

    /**
     * Email notification subject.
     */
    public static function getEmailSubject(
        Check $check, string $oldStatus, string $recipientRole
    ): string
    {
        $params = [
            'number' => CheckHelper::number($check),
            'orderNumber' => OrderHelper::number($check->stage->order),
        ];

        return match ($check->status) {
            /** By 3D modeler */
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value => __('checks.3d_check_accepted.by_modeler_3d.email_subject', $params),

            /** By clinical specialist */
            CheckStatusEnum::REJECTED_BY_CLINICAL_SPECIALIST->value => __('checks.3d_check_rejected.by_clinical_specialist.email_subject', $params),
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value => match ($oldStatus) {
                CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value => __('checks.3d_check_accepted.by_clinical_specialist.email_subject', $params),
                /** Recalled by clinical director */
                CheckStatusEnum::VERIFICATION_BY_DOCTOR->value => __('checks.3d_check_recalled.by_clinical_director.email_subject', $params),
            },

            /** By clinical director */
            CheckStatusEnum::REJECTED_BY_CLINICAL_DIRECTOR->value => __('checks.3d_check_rejected.by_clinical_director.email_subject', $params),
            CheckStatusEnum::VERIFICATION_BY_DOCTOR->value => __('checks.3d_check_accepted.by_clinical_director.email_subject', $params),

            /** By doctor */
            CheckStatusEnum::REJECTED_BY_DOCTOR->value => __('checks.3d_check_rejected.by_doctor.email_subject', $params),
            CheckStatusEnum::ACCEPTED->value => __('checks.3d_check_accepted.by_doctor.email_subject', $params),
        };
    }

    /**
     * Email notification message.
     */
    public static function getEmailMessage(
        Check $check, string $oldStatus, string $recipientRole, bool $linkable = true
    ): string
    {
        $params = [
            'number' => $linkable ? CheckHelper::getLink($check) : CheckHelper::number($check),
            'orderNumber' => $linkable ? OrderHelper::getLink($check->stage->order) : OrderHelper::number($check->stage->order),
            'reason' => $check->data?->reject_reason ?? null,
        ];

        return match ($check->status) {
            /** By 3D modeler */
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value => __('checks.3d_check_accepted.by_modeler_3d.email_message', $params),

            /** By clinical specialist */
            CheckStatusEnum::REJECTED_BY_CLINICAL_SPECIALIST->value => __('checks.3d_check_rejected.by_clinical_specialist.email_message', $params),
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value => match ($oldStatus) {
                CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value => __('checks.3d_check_accepted.by_clinical_specialist.email_message', $params),
                /** Recalled by clinical director */
                CheckStatusEnum::VERIFICATION_BY_DOCTOR->value => __('checks.3d_check_recalled.by_clinical_director.email_message', $params),
            },

            /** By clinical director */
            CheckStatusEnum::REJECTED_BY_CLINICAL_DIRECTOR->value => __('checks.3d_check_rejected.by_clinical_director.email_message', $params),
            CheckStatusEnum::VERIFICATION_BY_DOCTOR->value => __('checks.3d_check_accepted.by_clinical_director.email_message', $params),

            /** By doctor */
            CheckStatusEnum::REJECTED_BY_DOCTOR->value => __('checks.3d_check_rejected.by_doctor.email_message', $params),
            CheckStatusEnum::ACCEPTED->value => __('checks.3d_check_accepted.by_doctor.email_message', $params),
        };
    }

    /**
     * Chat notification message.
     */
    public static function getChatMessage(Check|CheckData $check, string $oldStatus, bool $linkable = false): string
    {
        $orderId = $check instanceof Check ? $check->stage->order_id : $check->orderId;

        $params = [
            'number' => $linkable ? CheckHelper::getLink($check) : CheckHelper::number($check),
            'orderNumber' => $linkable ? OrderHelper::getLink($orderId) : OrderHelper::number($orderId),
            'reason' => $check->data->reject_reason ?? null,
        ];

        return match ($check->status) {
            /** By 3D modeler */
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value => __('checks.3d_check_accepted.by_modeler_3d.chat_message', $params),

            /** By clinical specialist */
            CheckStatusEnum::REJECTED_BY_CLINICAL_SPECIALIST->value => __('checks.3d_check_rejected.by_clinical_specialist.chat_message', $params),
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value => match ($oldStatus) {
                CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value => __('checks.3d_check_accepted.by_clinical_specialist.chat_message', $params),
                /** Recalled by clinical director */
                CheckStatusEnum::VERIFICATION_BY_DOCTOR->value => __('checks.3d_check_recalled.by_clinical_director.chat_message', $params),
            },

            /** By clinical director */
            CheckStatusEnum::REJECTED_BY_CLINICAL_DIRECTOR->value => __('checks.3d_check_rejected.by_clinical_director.chat_message', $params),
            CheckStatusEnum::VERIFICATION_BY_DOCTOR->value => __('checks.3d_check_accepted.by_clinical_director.chat_message', $params),

            /** By doctor */
            CheckStatusEnum::REJECTED_BY_DOCTOR->value => __('checks.3d_check_rejected.by_doctor.chat_message', $params),
            CheckStatusEnum::ACCEPTED->value => __('checks.3d_check_accepted.by_doctor.chat_message', $params),
        };
    }

    /**
     * Chat message subtype.
     */
    public static function getChatMessageSubType(Check $check, string $oldStatus): string
    {
        return match ($check->status) {
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value => ChatMessageSubTypeEnum::MODELER_3D_CHECK_SENT_TO_VERIFICATION->value,

            CheckStatusEnum::REJECTED_BY_CLINICAL_SPECIALIST->value => ChatMessageSubTypeEnum::CLINICAL_SPECIALIST_CHECK_REJECTED->value,
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value => match ($oldStatus) {
                CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value => ChatMessageSubTypeEnum::CLINICAL_SPECIALIST_CHECK_SENT_TO_VERIFICATION->value,
                /** Recalled by clinical director */
                CheckStatusEnum::VERIFICATION_BY_DOCTOR->value => ChatMessageSubTypeEnum::CLINICAL_DIRECTOR_CHECK_RECALLED_FROM_DOCTOR->value,
            },

            CheckStatusEnum::REJECTED_BY_CLINICAL_DIRECTOR->value => ChatMessageSubTypeEnum::CLINICAL_DIRECTOR_CHECK_REJECTED->value,
            CheckStatusEnum::VERIFICATION_BY_DOCTOR->value => ChatMessageSubTypeEnum::CLINICAL_DIRECTOR_CHECK_SENT_TO_VERIFICATION->value,

            CheckStatusEnum::REJECTED_BY_DOCTOR->value => ChatMessageSubTypeEnum::DOCTOR_CHECK_REJECTED->value,
            CheckStatusEnum::ACCEPTED->value => ChatMessageSubTypeEnum::DOCTOR_CHECK_ACCEPTED->value,
        };
    }
}
