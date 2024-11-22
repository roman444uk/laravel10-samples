<?php

namespace App\Actions\Orders;

use App\Classes\ServicesResponses\OperationResponse;
use App\Enums\Orders\CheckStatusEnum;
use App\Models\Check;
use App\Models\User;
use App\Notifications\Orders\CheckVerifyingActionNotification;
use Illuminate\Database\Eloquent\Collection;

class CheckVerifyingActionNotifications
{
    public function handle(Check $check, string $oldStatus): OperationResponse
    {
        /** By 3D modeler */
        if ($check->status === CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value) {
            $this->verifyingActionNotificationSend($check, $oldStatus, $check->stage->clinicalSpecialist);
        }

        /** By clinical specialist */
        if ($check->status === CheckStatusEnum::REJECTED_BY_CLINICAL_SPECIALIST->value) {
            $this->verifyingActionNotificationSend($check, $oldStatus, $check->stage->modeler3d);
        }

        if ($check->status === CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value
            && $oldStatus === CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value
        ) {
            $this->verifyingActionNotificationSend($check, $oldStatus, $check->stage->modeler3d);
            $this->verifyingActionNotificationSend($check, $oldStatus, User::isClinicalDirector()->get());
        }

        /** By clinical director */
        if ($check->status === CheckStatusEnum::REJECTED_BY_CLINICAL_DIRECTOR->value) {
            $this->verifyingActionNotificationSend($check, $oldStatus, $check->stage->modeler3d);
        }

        if ($check->status === CheckStatusEnum::VERIFICATION_BY_DOCTOR->value) {
            $this->verifyingActionNotificationSend($check, $oldStatus, $check->stage->modeler3d);
            $this->verifyingActionNotificationSend($check, $oldStatus, $check->stage->order->doctor->user);
        }

        if ($check->status === CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value
            && $oldStatus === CheckStatusEnum::VERIFICATION_BY_DOCTOR->value
        ) {
            $this->verifyingActionNotificationSend($check, $oldStatus, $check->stage->modeler3d);
        }

        /** By doctor */
        if ($check->status === CheckStatusEnum::REJECTED_BY_DOCTOR->value) {
            $this->verifyingActionNotificationSend($check, $oldStatus, $check->stage->modeler3d);
            $this->verifyingActionNotificationSend($check, $oldStatus, $check->stage->clinicalSpecialist);
            $this->verifyingActionNotificationSend($check, $oldStatus, User::isClinicalDirector()->get());
        }

        if ($check->status === CheckStatusEnum::ACCEPTED->value) {
            $this->verifyingActionNotificationSend($check, $oldStatus, $check->stage->modeler3d);
            $this->verifyingActionNotificationSend($check, $oldStatus, $check->stage->clinicalSpecialist);
            $this->verifyingActionNotificationSend($check, $oldStatus, User::isClinicalDirector()->get());
        }

        return successOperationResponse();
    }

    /**
     * Process verifying action notification.
     */
    public function verifyingActionNotificationSend($check, $statusOld, Collection|User $user): void
    {
        if ($user instanceof User) {
            $user->notify(new CheckVerifyingActionNotification($check, $statusOld, $user->role));
        }

        if ($user instanceof Collection) {
            $service = $this;

            $user->each(function (User $clinicalDirector) use ($check, $statusOld, $service) {
                $service->verifyingActionNotificationSend($check, $statusOld, $clinicalDirector);
            });
        }
    }
}
