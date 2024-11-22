<?php

namespace App\Services\Db\Orders;

use App\Classes\Helpers\Db\StageHelper;
use App\Enums\Orders\StageStatusEnum;
use App\Models\Repositories\UserRepository;
use App\Models\Stage;

class StageEmployeesService
{
    public function __construct(
        protected UserRepository               $userRepository,
    )
    {
    }

    /**
     * Assign free employees.
     */
    public function assignFreeEmployees(Stage $stage): void
    {
        $this->assignFreeClinicalSpecialist($stage);
        $this->assignFreeModeler3d($stage);
        $this->assignFreeTechnicianDigitizer($stage);
        $this->assignFreeTechnicianProduction($stage);
        $this->assignFreeLogisticManager($stage);
    }

    /**
     * Assign clinical specialist to model if it hasn't been assigned yet.
     */
    public function assignFreeClinicalSpecialist(Stage $stage): void
    {
        if (!$stage->clinical_specialist_id && StageHelper::hasReachedStatus($stage, StageStatusEnum::VERIFICATION->value)) {
            $stage->update([
                'clinical_specialist_id' => $this->userRepository->getFreeClinicalSpecialist()?->id
            ]);
        }
    }

    /**
     * Assign 3D modeler to model if it hasn't been assigned yet.
     */
    public function assignFreeModeler3d(Stage $stage): void
    {
        if (!$stage->modeler_3d_id && StageHelper::hasReachedStatus($stage, StageStatusEnum::MODELING->value)) {
            $stage->update([
                'modeler_3d_id' => $this->userRepository->getFreeModeler3d()?->id
            ]);
        }
    }

    /**
     * Assign technician digitizer to model if it hasn't been assigned yet.
     */
    public function assignFreeTechnicianDigitizer(Stage $stage): void
    {
        if (!$stage->technician_digitizer_id && StageHelper::hasReachedStatus($stage, StageStatusEnum::PREPARATION->value)) {
            $stage->update([
                'technician_digitizer_id' => $this->userRepository->getFreeTechnicianDigitizer()?->id
            ]);
        }
    }

    /**
     * Assign technician production to model if it hasn't been assigned yet.
     */
    public function assignFreeTechnicianProduction(Stage $stage): void
    {
        if (!$stage->technician_production_id && StageHelper::hasReachedStatus($stage, StageStatusEnum::PRODUCTION_RELEASE->value)) {
            $stage->update([
                'technician_production_id' => $this->userRepository->getFreeTechnicianProduction()?->id
            ]);
        }
    }

    /**
     * Assign technician production to model if it hasn't been assigned yet.
     */
    public function assignFreeLogisticManager(Stage $stage): void
    {
        if (!$stage->logistic_manager_id && StageHelper::hasReachedStatus($stage, StageStatusEnum::DELIVERY_PREPARATION->value)) {
            $stage->update([
                'logistic_manager_id' => $this->userRepository->getFreeLogisticManager()?->id
            ]);
        }
    }
}
