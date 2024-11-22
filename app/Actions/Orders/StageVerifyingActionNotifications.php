<?php

namespace App\Actions\Orders;

use App\Classes\ServicesResponses\OperationResponse;
use App\Enums\Orders\StageStatusEnum;
use App\Models\Stage;
use App\Models\User;
use App\Notifications\Orders\StageVerifyingActionNotification;
use Illuminate\Database\Eloquent\Collection;

class StageVerifyingActionNotifications
{
    public function handle(Stage $stage, string $oldStatus): OperationResponse
    {
        if ($stage->status === StageStatusEnum::DRAFT->value && $oldStatus === StageStatusEnum::VERIFICATION->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, $stage->order->doctor->user);
        }

        if ($stage->status === StageStatusEnum::VERIFICATION->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, $stage->clinicalSpecialist);
        }

        if ($stage->status === StageStatusEnum::PREPARATION->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, $stage->technicianDigitizer);
        }

        if ($stage->status === StageStatusEnum::MODELING->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, $stage->modeler3d);
        }

        if ($stage->status === StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, $stage->clinicalSpecialist);
        }

        if ($stage->status === StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, User::isClinicalDirector()->get());
        }

        if ($stage->status === StageStatusEnum::CHECK_VERIFICATION_BY_DOCTOR->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, $stage->order->doctor->user);
        }

        if ($stage->status === StageStatusEnum::PAYMENT_BILL_AFTER_REJECTION->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, User::isClinicalDirector()->get());
        }

        if ($stage->status === StageStatusEnum::PAYMENT_BILL->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, User::isClinicalDirector()->get());
        }

        if ($stage->status === StageStatusEnum::PAYMENT_AWAITING->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, $stage->order->doctor->user);
        }

        if ($stage->status === StageStatusEnum::PRODUCTION_OPTIONS->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, $stage->order->doctor->user);
        }

        if ($stage->status === StageStatusEnum::PRODUCTION_PREPARATION->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, User::isProductionManager()->get());
        }

        if ($stage->status === StageStatusEnum::PRODUCTION_RELEASE->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, $stage->technicianProduction);
        }

        if ($stage->status === StageStatusEnum::PRODUCTION_CONTROL->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, User::isProductionManager()->get());
        }

        if ($stage->status === StageStatusEnum::DELIVERY_PREPARATION->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, $stage->logisticManager);
        }

        if ($stage->status === StageStatusEnum::DELIVERED->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, $stage->order->doctor->user);
        }

        if ($stage->status === StageStatusEnum::TREATMENT->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, User::isClinicalDirector()->get());
        }

        if ($stage->status === StageStatusEnum::COMPLETED->value) {
            $this->verifyingActionNotificationSend($stage, $oldStatus, User::isClinicalDirector()->get());
        }

        return successOperationResponse();
    }

    /**
     * Process verifying action notification.
     */
    public function verifyingActionNotificationSend($stage, $oldStatus, Collection|User $user = null): void
    {
        if (!$user) {
            return;
        }

        if ($user instanceof User) {
            $user->notify(new StageVerifyingActionNotification($stage, $oldStatus, $user->role));
        }

        if ($user instanceof Collection) {
            $service = $this;

            $user->each(function (User $clinicalDirector) use ($stage, $oldStatus, $service) {
                $service->verifyingActionNotificationSend($stage, $oldStatus, $clinicalDirector);
            });
        }
    }
}
