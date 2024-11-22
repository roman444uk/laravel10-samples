<?php

namespace App\Services\Db\Orders;

use App\Actions\Orders\CheckVerifyingActionChatQuickMessages;
use App\Actions\Orders\CheckVerifyingActionNotifications;
use App\Classes\ServicesResponses\OperationResponse;
use App\Enums\Orders\CheckStatusEnum;
use App\Enums\Users\UserRoleEnum;
use App\Events\Orders\CheckStatusChangedEvent;
use App\Models\Check;

class CheckLifeCycleService
{
    /**
     * Send check to verification chain by 3D modeler.
     */
    public function sendToVerification(Check $check): OperationResponse
    {
        if (!$check->viewer_url) {
            return errorOperationResponse(null, [
                'viewer_url' => __('checks.viewer_url_is_empty_for_sending_to_verification')
            ]);
        }

        $this->updateFromVerifyingAction($check, [
            'status' => CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value
        ]);

        return successOperationResponse([
            'check' => $check,
        ]);
    }

    /**
     * Accept check.
     */
    public function accept(Check $check): OperationResponse
    {
        if (!in_array($check->status, [
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value,
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value,
            CheckStatusEnum::VERIFICATION_BY_DOCTOR->value,
        ])) {
            return errorOperationResponse();
        }

        $status = match (getUserRole()) {
            UserRoleEnum::CLINICAL_SPECIALIST->value => CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value,
            UserRoleEnum::CLINICAL_DIRECTOR->value => CheckStatusEnum::VERIFICATION_BY_DOCTOR->value,
            UserRoleEnum::DOCTOR->value => CheckStatusEnum::ACCEPTED->value,
        };

        $this->updateFromVerifyingAction($check, [
            'status' => $status
        ]);

        return successOperationResponse([
            'check' => $check,
        ]);
    }

    /**
     * Reject check.
     */
    public function reject(Check $check, string $rejectReason): OperationResponse
    {
        if (!in_array($check->status, [
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value,
            CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value,
            CheckStatusEnum::VERIFICATION_BY_DOCTOR->value,
        ])) {
            return errorOperationResponse();
        }

        $status = match (getUserRole()) {
            UserRoleEnum::CLINICAL_SPECIALIST->value => CheckStatusEnum::REJECTED_BY_CLINICAL_SPECIALIST->value,
            UserRoleEnum::CLINICAL_DIRECTOR->value => CheckStatusEnum::REJECTED_BY_CLINICAL_DIRECTOR->value,
            UserRoleEnum::DOCTOR->value => CheckStatusEnum::REJECTED_BY_DOCTOR->value,
        };

        $this->updateFromVerifyingAction($check, [
            'status' => $status,
            'data->reject_reason' => $rejectReason,
        ]);

        return successOperationResponse([
            'check' => $check,
        ]);
    }

    /**
     * Recall from doctor's verification back to clinical director's verification.
     */
    public function recallFromDoctorVerification(Check $check): OperationResponse
    {
        if ($check->status !== CheckStatusEnum::VERIFICATION_BY_DOCTOR->value) {
            return successOperationResponse([
                'check' => $check,
            ]);
        }

        $this->updateFromVerifyingAction($check, [
            'status' => CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value,
        ]);

        return successOperationResponse([
            'check' => $check,
        ]);
    }

    /**
     * Update model with appropriate to verify action data and call notifications method.
     */
    public function updateFromVerifyingAction(Check $check, array $data): void
    {
        $oldStatus = $check->status;

        $check->update($data);

        app()->make(StageLifeCycleService::class)->syncStatusWithCheck($check);

        if ($check->status !== $oldStatus) {
            CheckStatusChangedEvent::dispatch($check, $oldStatus);
//            $this->verifyingActionNotifications($check, $oldStatus);
        }
    }

    /**
     * Send all notifications.
     */
    public function statusChanged(Check $check, string $statusOld): OperationResponse
    {
        $this->verifyingActionNotifications($check, $statusOld);

        return successOperationResponse();
    }

    /**
     * Send notifications about check verifying actions.
     */
    protected function verifyingActionNotifications(Check $check, string $oldStatus): void
    {
        app(CheckVerifyingActionChatQuickMessages::class)->handle($check, $oldStatus);

        app(CheckVerifyingActionNotifications::class)->handle($check, $oldStatus);
    }
}
