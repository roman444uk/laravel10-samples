<?php

namespace App\Services\Db\Orders;

use App\Actions\Orders\StageVerifyingActionChatQuickMessages;
use App\Actions\Orders\StageVerifyingActionNotifications;
use App\Classes\ServicesResponses\OperationResponse;
use App\Data\StageData;
use App\Enums\Orders\CheckStatusEnum;
use App\Enums\Orders\ProductionStatusEnum;
use App\Enums\Orders\StageStatusEnum;
use App\Models\Check;
use App\Models\Production;
use App\Models\Stage;

class StageLifeCycleService
{
    /**
     * Synchronize stage's status witch check's status. Used while check is going throw its lifecycle.
     */
    public function syncStatusWithCheck(Check $check): OperationResponse
    {
        $status = match ($check->status) {
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value => StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value,
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value => StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value,
            CheckStatusEnum::VERIFICATION_BY_DOCTOR->value => StageStatusEnum::CHECK_VERIFICATION_BY_DOCTOR->value,

            CheckStatusEnum::ACCEPTED->value => StageStatusEnum::PAYMENT_BILL->value,

            CheckStatusEnum::REJECTED_BY_CLINICAL_SPECIALIST->value,
            CheckStatusEnum::REJECTED_BY_CLINICAL_DIRECTOR->value,
            CheckStatusEnum::REJECTED_BY_DOCTOR->value => StageStatusEnum::MODELING->value,
        };

        app()->make(StageService::class)->update($check->stage, StageData::from([
            'status' => $status
        ]));

        return successOperationResponse([
            'stage' => $check->stage
        ]);
    }

    /**
     * Synchronize stage's status witch production's status. Used while production is going throw its lifecycle.
     */
    public function syncStatusWithProduction(Production $production): OperationResponse
    {
        $status = match ($production->status) {
            ProductionStatusEnum::NEW->value,
            ProductionStatusEnum::PRODUCTION->value => StageStatusEnum::PRODUCTION_PREPARATION->value,
            default => null,
        };

        app()->make(StageService::class)->update($production->check->stage, StageData::from([
            'status' => $status,
        ]));

        return successOperationResponse([
            'stage' => $production->check->stage,
        ]);
    }

    /**
     * Send all notifications
     */
    public function statusChanged(Stage $stage, string $statusOld, Check $actualCheck = null): OperationResponse
    {
        $this->verifyingActionNotifications($stage, $statusOld);

        return successOperationResponse();
    }

    /**
     * Send notifications about check verifying actions.
     */
    protected function verifyingActionNotifications(Stage $stage, string $statusOld): void
    {
        // These notifications are sent on behalf of Check
        if (
            (
                $stage->status === StageStatusEnum::MODELING->value && in_array($statusOld, [
                    StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value,
                    StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value,
                    StageStatusEnum::CHECK_VERIFICATION_BY_DOCTOR->value
                ])
            ) || (
                $stage->status === StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value
                && $statusOld === StageStatusEnum::MODELING->value
            ) || (
                $stage->status === StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value
                && $statusOld === StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value
            ) || (
                $stage->status === StageStatusEnum::CHECK_VERIFICATION_BY_DOCTOR->value
                && $statusOld === StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value
            ) || (
                $stage->status === StageStatusEnum::PAYMENT_BILL->value
                && $statusOld === StageStatusEnum::CHECK_VERIFICATION_BY_DOCTOR->value
            )
        ) {
            return;
        }

        app(StageVerifyingActionChatQuickMessages::class)->handle($stage, $statusOld);

        app(StageVerifyingActionNotifications::class)->handle($stage, $statusOld);
    }
}
