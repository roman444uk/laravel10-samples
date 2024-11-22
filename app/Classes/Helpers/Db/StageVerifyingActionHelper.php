<?php

namespace App\Classes\Helpers\Db;

use App\Enums\Chat\ChatMessageSubTypeEnum;
use App\Enums\NotifySourceEnum;
use App\Enums\Orders\StageStatusEnum;
use App\Enums\Users\UserRoleEnum;
use App\Models\Stage;

class StageVerifyingActionHelper
{
    public static function getSource(
        Stage|array $stage, string $oldStatus, NotifySourceEnum|string $type, string $recipientRole = null, bool $linkable = false
    ): ?string
    {
        $orderId = $stage instanceof Stage ? $stage->order->id : $stage['order_id'];
        $status = $stage instanceof Stage ? $stage->status : $stage['stage_status'];

        $params = [
            'orderNumber' => OrderHelper::number($orderId),
            'orderNumberLink' => $linkable ? OrderHelper::getLink($orderId) : OrderHelper::number($orderId),
        ];

        $data = match ($status) {
            StageStatusEnum::DRAFT->value => match ($oldStatus) {
                StageStatusEnum::VERIFICATION->value => [
                    'chat' => __('Клинический специалист вернул заказ на доработку доктору', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_DRAFT_FROM_VERIFICATION->value,
                    'notify' => match ($recipientRole) {
                        UserRoleEnum::DOCTOR->value => __('Заказ :orderNumberLink возвращён на доработку', $params),
                        default => __('Клинический специалист вернул заказ :orderNumberLink на доработку доктору', $params),
                    },
                    'email-subj' => match ($recipientRole) {
                        UserRoleEnum::DOCTOR->value => __('Заказ :orderNumber возвращён на доработку', $params),
                        default => __('Клинический специалист вернул заказ :orderNumber на доработку доктору', $params),
                    },
                    'email-body' => match ($recipientRole) {
                        UserRoleEnum::DOCTOR->value => __('Заказ :orderNumberLink возвращён на доработку', $params),
                        default => __('Клинический специалист вернул заказ :orderNumberLink на доработку доктору', $params),
                    },
                ],
                default => null,
            },

            StageStatusEnum::VERIFICATION->value => match ($oldStatus) {
                StageStatusEnum::DRAFT->value => [
                    'chat' => __('Доктор отправил заказ в работу'),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_VERIFICATION->value,
                    'notify' => match ($recipientRole) {
                        UserRoleEnum::CLINICAL_SPECIALIST->value => __('Заказ :orderNumberLink отправлен в работу', $params),
                        default => __('Доктор отправил заказ :orderNumberLink в работу', $params),
                    },
                    'email-subj' => match ($recipientRole) {
                        UserRoleEnum::CLINICAL_SPECIALIST->value => __('Заказ :orderNumber отправлен в работу', $params),
                        default => __('Доктор отправил заказ :orderNumber в работу', $params),
                    },
                    'email-body' => match ($recipientRole) {
                        UserRoleEnum::CLINICAL_SPECIALIST->value => __('Заказ :orderNumberLink отправлен в работу', $params),
                        default => __('Доктор отправил заказ :orderNumberLink в работу', $params),
                    }
                ],
                StageStatusEnum::PREPARATION->value => [
                    'chat' => __('Техник оцифровщик вернул заказ на доработку клиническому специалисту на доработку', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_VERIFICATION_FROM_PREPARATION->value,
                    'notify' => match ($recipientRole) {
                        UserRoleEnum::CLINICAL_SPECIALIST->value => __('Заказ :orderNumberLink возвращён на доработку', $params),
                        default => __('Техник оцифровщик вернул заказ :orderNumberLink на доработку клиническому специалисту', $params),
                    },
                    'email-subj' => match ($recipientRole) {
                        UserRoleEnum::CLINICAL_SPECIALIST->value => __('Заказ :orderNumberLink возвращён на доработку', $params),
                        default => __('Техник оцифровщик вернул заказ :orderNumber на доработку возвращён на доработку', $params),
                    },
                    'email-body' => match ($recipientRole) {
                        UserRoleEnum::CLINICAL_SPECIALIST->value => __('Заказ :orderNumberLink возвращён на доработку', $params),
                        default => __('Техник оцифровщик вернул заказ :orderNumberLink на доработку клиническому специалисту', $params),
                    },
                ],
                default => null,
            },

            StageStatusEnum::PREPARATION->value => match ($oldStatus) {
                StageStatusEnum::VERIFICATION->value => [
                    'chat' => __('Клинический специалист отправил заказ в подготовку', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_PREPARATION->value,
                    'notify' => match ($recipientRole) {
                        UserRoleEnum::TECHNICIAN_DIGITIZER->value => __('Заказ :orderNumberLink отправлен в подготовку', $params),
                        default => __('Клинический специалист отправил заказ :orderNumberLink в подготовку', $params),
                    },
                    'email-subj' => match ($recipientRole) {
                        UserRoleEnum::TECHNICIAN_DIGITIZER->value => __('Заказ :orderNumber отправлен в подготовку', $params),
                        default => __('Клинический специалист отправил заказ :orderNumber в подготовку', $params),
                    },
                    'email-body' => match ($recipientRole) {
                        UserRoleEnum::TECHNICIAN_DIGITIZER->value => __('Заказ :orderNumberLink отправлен в подготовку', $params),
                        default => __('Клинический специалист отправил заказ :orderNumberLink в подготовку', $params),
                    },
                ],
                StageStatusEnum::MODELING->value => [
                    'chat' => __('3D моделировщик вернул заказ в подготовку'),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_PREPARATION_FROM_MODELING->value,
                    'notify' => match ($recipientRole) {
                        UserRoleEnum::TECHNICIAN_DIGITIZER->value => __('Заказ :orderNumberLink возвращён в подготовку', $params),
                        default => __('3D моделировщик вернул заказ :orderNumberLink в подготовку', $params),
                    },
                    'email-subj' => match ($recipientRole) {
                        UserRoleEnum::TECHNICIAN_DIGITIZER->value => __('Заказ :orderNumber возвращён в подготовку', $params),
                        default => __('3D моделировщик вернул заказ :orderNumber в подготовку', $params),
                    },
                    'email-body' => match ($recipientRole) {
                        UserRoleEnum::TECHNICIAN_DIGITIZER->value => __('Заказ :orderNumberLink возвращён в подготовку', $params),
                        default => __('3D моделировщик вернул заказ :orderNumberLink в подготовку', $params),
                    },
                ],
                default => null,
            },

            StageStatusEnum::MODELING->value => match ($oldStatus) {
                StageStatusEnum::PREPARATION->value => [
                    'chat' => __('Техник оцифровщик отправил заказ в моделирование'),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_MODELING->value,
                    'notify' => match ($recipientRole) {
                        UserRoleEnum::MODELER_3D->value => __('Заказ :orderNumberLink отправлен в моделирование', $params),
                        default => __('3D моделировщик отпраил заказ :orderNumberLink в моделирование', $params),
                    },
                    'email-subj' => match ($recipientRole) {
                        UserRoleEnum::MODELER_3D->value => __('Заказ :orderNumber отправлен в моделирование', $params),
                        default => __('3D моделировщик отпраил заказ :orderNumber в моделирование', $params),
                    },
                    'email-body' => match ($recipientRole) {
                        UserRoleEnum::MODELER_3D->value => __('Заказ :orderNumberLink отправлен в моделирование', $params),
                        default => __('3D моделировщик отпраил заказ :orderNumberLink в моделирование', $params),
                    },
                ],
                // Handled by check
                StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value => [
                    'chat' => __('Клинический специалист вернул заказ на доработку 3D моделировщику', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_MODELING_FROM_CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value,
                    'notify' => '',
                    'email-subj' => '',
                    'email-body' => ''
                ],
                // Handled by check
                StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value => [
                    'chat' => __('Клинический директор вернул заказ на доработку 3D моделировщику', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_MODELING_FROM_CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value,
                    'notify' => '',
                    'email-subj' => '',
                    'email-body' => ''
                ],
                // Handled by check
                StageStatusEnum::CHECK_VERIFICATION_BY_DOCTOR->value => [
                    'chat' => __('Доктор вернул заказ на доработку 3D моделировщику', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_MODELING_FROM_VERIFICATION_BY_DOCTOR->value,
                    'notify' => '',
                    'email-subj' => '',
                    'email-body' => ''
                ],
                default => null,
            },

            StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value => match ($oldStatus) {
                // Handled by check
                StageStatusEnum::MODELING->value => [
                    'chat' => __('3D моделировщик отправил заказ на проверку клиническому специалисту', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value,
                    'notify' => '',
                    'email-subj' => '',
                    'email-body' => ''
                ],
                /*StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value => [
                    'chat' => __('Клинический директор вернул заказ на доработку клиническому специалисту'),
                    'chat-sub-type' => '',
                    'notify' => '',
                    'email-subj' => '',
                    'email-body' => ''
                ],*/
                default => null,
            },

            StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value => match ($oldStatus) {
                // Handled by check
                StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value => [
                    'chat' => __('Клинический специалист отправил заказ на проверку клиническому директору', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value,
                    'notify' => '',
                    'email-subj' => '',
                    'email-body' => ''
                ],
                default => null,
            },

            StageStatusEnum::CHECK_VERIFICATION_BY_DOCTOR->value => match ($oldStatus) {
                // Handled by check
                StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value => [
                    'chat' => __('Клинический директор отправил заказ на проверку доктору', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_CHECK_VERIFICATION_BY_DOCTOR->value,
                    'notify' => '',
                    'email-subj' => '',
                    'email-body' => ''
                ],
                default => null,
            },

            StageStatusEnum::PAYMENT_BILL->value => match ($oldStatus) {
                // Handled by check
                StageStatusEnum::CHECK_VERIFICATION_BY_DOCTOR->value => [
                    'chat' => __('Доктор подтвердил лечение'),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_PAYMENT_BILL->value,
                    'notify' => '',
                    'email-subj' => '',
                    'email-body' => ''
                ],
                default => null,
            },

            StageStatusEnum::PAYMENT_BILL_AFTER_REJECTION->value => match ($oldStatus) {
                StageStatusEnum::CHECK_VERIFICATION_BY_DOCTOR->value => [
                    'chat' => __('Доктор отказался от лечения', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_PAYMENT_BILL_AFTER_REJECTION->value,
                    'notify' => __('По заказу :orderNumberLink доктор отказался от лечения', $params),
                    'email-subj' => __('По заказу :orderNumber доктор отказался от лечения', $params),
                    'email-body' => __('По заказу :orderNumberLink доктор отказался от лечения', $params),
                ],
                default => null,
            },

            StageStatusEnum::PAYMENT_AWAITING->value => match ($oldStatus) {
                StageStatusEnum::PAYMENT_BILL->value => [
                    'chat' => __('Счёт выставлен, ожидается оплата от доктора', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_PAYMENT_AWAITING->value,
                    'notify' => match ($recipientRole) {
                        UserRoleEnum::DOCTOR->value => __('По заказу :orderNumberLink ожидается оплата', $params),
                        default => __('По заказу :orderNumberLink счёт выставлен, ожидается оплата от доктора', $params),
                    },
                    'email-subj' => match ($recipientRole) {
                        UserRoleEnum::DOCTOR->value => __('По заказу :orderNumber ожидается оплата', $params),
                        default => __('По заказу :orderNumber счёт выставлен, ожидается оплата от доктора', $params),
                    },
                    'email-body' => match ($recipientRole) {
                        UserRoleEnum::DOCTOR->value => __('По заказу :orderNumberLink ожидается оплата', $params),
                        default => __('По заказу :orderNumberLink счёт выставлен, ожидается оплата от доктора', $params),
                    },
                ],
                default => null,
            },

            StageStatusEnum::PAYMENT_AWAITING_AFTER_REJECTION->value => match ($oldStatus) {
                StageStatusEnum::PAYMENT_BILL_AFTER_REJECTION->value => [
                    'chat' => __('Счёт выставлен, ожидается оплата от доктора', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_PAYMENT_AWAITING_FROM_PAYMENT_BILL_AFTER_REJECTION->value,
                    'notify' => match ($recipientRole) {
                        UserRoleEnum::DOCTOR->value => __('По заказу :orderNumberLink ожидается оплата', $params),
                        default => __('По заказу :orderNumberLink счёт выставлен, ожидается оплата от доктора', $params),
                    },
                    'email-subj' => match ($recipientRole) {
                        UserRoleEnum::DOCTOR->value => __('По заказу :orderNumber ожидается оплата', $params),
                        default => __('По заказу :orderNumber счёт выставлен, ожидается оплата от доктора', $params),
                    },
                    'email-body' => match ($recipientRole) {
                        UserRoleEnum::DOCTOR->value => __('По заказу :orderNumberLink ожидается оплата', $params),
                        default => __('По заказу :orderNumberLink счёт выставлен, ожидается оплата от доктора', $params),
                    },
                ],
                default => null,
            },

            StageStatusEnum::PRODUCTION_OPTIONS->value => match ($oldStatus) {
                StageStatusEnum::PAYMENT_AWAITING->value => [
                    'chat' => __('Оплата получена, заказ передан доктору для выбора кол-во шагов', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_PRODUCTION_OPTIONS->value,
                    'notify' => match ($recipientRole) {
                        UserRoleEnum::DOCTOR->value => __('По заказу :orderNumberLink плата получена, заказ передан для выбора кол-во шагов', $params),
                        default => __('По заказу :orderNumberLink плата получена, заказ передан доктору для выбора кол-во шагов', $params),
                    },
                    'email-subj' => match ($recipientRole) {
                        UserRoleEnum::DOCTOR->value => __('По заказу :orderNumber плата получена, заказ передан для выбора кол-во шагов', $params),
                        default => __('По заказу :orderNumberLink плата получена, заказ передан доктору для выбора кол-во шагов', $params),
                    },
                    'email-body' => match ($recipientRole) {
                        UserRoleEnum::DOCTOR->value => __('По заказу :orderNumber плата получена, заказ передан для выбора кол-во шагов', $params),
                        default => __('По заказу :orderNumberLink плата получена, заказ передан доктору для выбора кол-во шагов', $params),
                    },
                ],
                default => null,
            },

            StageStatusEnum::PRODUCTION_PREPARATION->value => match ($oldStatus) {
                StageStatusEnum::PRODUCTION_OPTIONS->value => [
                    'chat' => __('Доктор выбрал кол-во шагов и заказ передан менеджеру производства на подготовку', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_PRODUCTION_PREPARATION->value,
                    'notify' => __('По заказу :orderNumberLink доктор выбрал кол-во шагов', $params),
                    'email-subj' => __('По заказу :orderNumber доктор выбрал кол-во шагов', $params),
                    'email-body' => __('По заказу :orderNumberLink доктор выбрал кол-во шагов', $params),
                ],
                StageStatusEnum::TREATMENT->value => [
                    'chat' => __('Доктор запросил продолжение'),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_PRODUCTION_OPTIONS_FROM_TREATMENT->value,
                    'notify' => __('По заказу :orderNumberLink доктор запросил продолжение', $params),
                    'email-subj' => __('По заказу :orderNumber доктор запросил продолжение', $params),
                    'email-body' => __('По заказу :orderNumberLink доктор запросил продолжение', $params),
                ],
                default => null,
            },

            StageStatusEnum::PRODUCTION_RELEASE->value => match ($oldStatus) {
                StageStatusEnum::PRODUCTION_PREPARATION->value => [
                    'chat' => __('Менеджер производства передал заказ технику производства для производства кап', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_PRODUCTION_RELEASE->value,
                    'notify' => __(' Заказ :orderNumberLink передан для производства кап', $params),
                    'email-subj' => __(' Заказ :orderNumber передан для производства кап', $params),
                    'email-body' =>__(' Заказ :orderNumberLink передан для производства кап', $params),
                ],
                default => null,
            },

            StageStatusEnum::PRODUCTION_PACKAGING->value => match ($oldStatus) {
                StageStatusEnum::PRODUCTION_RELEASE->value => [
                    'chat' => __('Заказ передан в стадию фасовки', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_PRODUCTION_PACKAGING->value,
                    'notify' => __(' Заказ :orderNumberLink передан в стадию фасовки', $params),
                    'email-subj' => __(' Заказ :orderNumber передан в стадию фасовки', $params),
                    'email-body' =>__(' Заказ :orderNumberLink передан в стадию фасовки', $params),
                ],
                default => null,
            },

            StageStatusEnum::PRODUCTION_CONTROL->value => match ($oldStatus) {
                StageStatusEnum::PRODUCTION_PACKAGING->value => [
                    'chat' => __('Техник производства передал заказ менеджеру производства на проверку', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_PRODUCTION_CONTROL->value,
                    'notify' => __(' Заказ :orderNumberLink передан для проверки производства', $params),
                    'email-subj' => __(' Заказ :orderNumber передан для проверки производства', $params),
                    'email-body' =>__(' Заказ :orderNumberLink передан для проверки производства', $params),
                ],
                default => null,
            },

            StageStatusEnum::DELIVERY_PREPARATION->value => match ($oldStatus) {
                StageStatusEnum::PRODUCTION_CONTROL->value => [
                    'chat' => __('Заказ передан менеджеру логистики на подготовку доставки', $params),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_DELIVERY_PREPARATION->value,
                    'notify' => __(' Заказ :orderNumberLink передан для подготовки доставки', $params),
                    'email-subj' => __(' Заказ :orderNumber передан для подготовки доставки', $params),
                    'email-body' =>__(' Заказ :orderNumberLink передан для подготовки доставки', $params),
                ],
                default => null,
            },

            StageStatusEnum::DELIVERY->value => match ($oldStatus) {
                StageStatusEnum::DELIVERY_PREPARATION->value => [
                    'chat' => __('Заказ передан в курьерскую службу для доставки'),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_DELIVERY->value,
                    'notify' => __(' Заказ :orderNumberLink передан в курьерскую службу для доставки', $params),
                    'email-subj' => __(' Заказ :orderNumber передан в курьерскую службу для доставки', $params),
                    'email-body' =>__(' Заказ :orderNumberLink передан в курьерскую службу для доставки', $params),
                ],
                default => null,
            },

            StageStatusEnum::DELIVERED->value => match ($oldStatus) {
                StageStatusEnum::DELIVERY->value => [
                    'chat' => __('Заказ доставлен'),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_DELIVERED->value,
                    'notify' => __(' Заказ :orderNumberLink доставлен', $params),
                    'email-subj' => __(' Заказ :orderNumber доставлен', $params),
                    'email-body' =>__(' Заказ :orderNumberLink доставлен', $params),
                ],
                default => null,
            },

            StageStatusEnum::TREATMENT->value => match ($oldStatus) {
                StageStatusEnum::DELIVERED->value => [
                    'chat' => __('Лечение начато'),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_TREATMENT->value,
                    'notify' => __('По заказу :orderNumberLink начато лечение', $params),
                    'email-subj' => __(' По заказу :orderNumber начато лечение', $params),
                    'email-body' =>__(' По заказу :orderNumberLink начато лечение', $params),
                ],
                default => null,
            },

            StageStatusEnum::COMPLETED->value => match ($oldStatus) {
                StageStatusEnum::TREATMENT->value => [
                    'chat' => __('Лечение завершено'),
                    'chat-sub-type' => ChatMessageSubTypeEnum::STAGE_TO_COMPLETED->value,
                    'notify' => __('По заказу :orderNumberLink завершено лечение', $params),
                    'email-subj' => __(' По заказу :orderNumber завершено лечение', $params),
                    'email-body' =>__(' По заказу :orderNumberLink завершено лечение', $params),
                ],
                default => null,
            },
        };

        return $data[$type] ?? null;
    }
}
