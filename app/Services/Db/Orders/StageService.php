<?php

namespace App\Services\Db\Orders;

use App\Classes\Helpers\Db\StageHelper;
use App\Classes\ServicesResponses\OperationResponse;
use App\Classes\ServicesResponses\OperationResponseError;
use App\Data\StageData;
use App\Enums\Orders\ProductionDeliveryStatusEnum;
use App\Enums\Orders\StageStatusEnum;
use App\Enums\Orders\StageTypeEnum;
use App\Events\Orders\StageStatusChangedEvent;
use App\Models\Address;
use App\Models\Check;
use App\Models\Order;
use App\Models\Production;
use App\Models\Repositories\StageRepository;
use App\Models\Repositories\UserRepository;
use App\Models\Stage;
use App\Notifications\Orders\StageTakeCastsNotification;
use App\Services\Db\Chat\ChatQuickMessageService;
use App\Services\Db\Chat\ChatService;
use App\Services\Db\Chat\ChatSyncService;
use App\Services\Db\System\FileService;
use Illuminate\Support\Collection;

class StageService
{
    public function __construct(
        protected CheckService                 $checkService,
        protected FileService                  $fileService,
        protected StageFieldsService           $stageFieldsService,
        protected StageFieldsLaboratoryService $stageFieldsLaboratoryService,
        protected StageRepository              $stageRepository,
        protected UserRepository               $userRepository,
    )
    {
    }

    /**
     * Create new model with type treatment.
     */
    public function storeTreatment(Order $order): OperationResponse
    {
        return $this->store($order, StageTypeEnum::TREATMENT->value);
    }

    /**
     * Create new model with type correction.
     */
    public function storeCorrection(Order $order): OperationResponse
    {
        return $this->store($order, StageTypeEnum::CORRECTION->value);
    }

    /**
     * Create new model.
     */
    public function store(Order $order, string $type): OperationResponse
    {
        $stageData = StageData::from([
            'orderId' => $order->id,
            'type' => $type,
            'status' => StageStatusEnum::DRAFT->value,
            'number' => $type === StageTypeEnum::CORRECTION->value
                ? Stage::where(['order_id' => $order->id, 'type' => $type])->count() + 1 : 0,
        ]);

        $stage = $this->stageRepository->create($stageData);

        if ($type === StageTypeEnum::CORRECTION->value) {
            Stage::where([
                'order_id' => $order->id,
                'number' => $stage->number - 1
            ])->update([
                'status' => StageStatusEnum::COMPLETED->value,
                'guarantee_date' => null,
            ]);
        }

        if (!$stage || !$stage->wasRecentlyCreated) {
            return errorOperationResponse();
        }

        app(StageEmployeesService::class)->assignFreeEmployees($stage);

        return successOperationResponse([
            'stage' => $stage,
        ]);
    }

    /**
     * Update model.
     */
    public function update(Stage $stage, StageData $stageData): OperationResponse
    {
        $statusOld = $stage->status;

        $stage->fill([
            'order_id' => $stageData->orderId ?? $stage->order_id,
            'clinical_specialist_id' => $stageData->getIncome('clinicalSpecialistId', $stage->clinical_specialist_id),
            'modeler_3d_id' => $stageData->getIncome('modeler3dId', $stage->modeler_3d_id),
            'technician_production_id' => $stageData->getIncome('technicianProductionId', $stage->technician_production_id),
            'technician_digitizer_id' => $stageData->getIncome('technicianDigitizerId', $stage->technician_digitizer_id),
            'logistic_manager_id' => $stageData->getIncome('logisticManagerId', $stage->logistic_manager_id),
            'type' => $stageData->type ?? $stage->type,
            'status' => $stageData->status ?? $stage->status,
            'delivery_address_id' => $stageData->deliveryAddressId ?? $stage->delivery_address_id,
            'number' => $stageData->number ?? $stage->number,
            'fields_text' => $stageData->fieldsText ?? $stage->fields_text,
            'fields_type' => $stageData->fieldsType ?? $stage->fields_type,
        ]);

        // save stage fields directly if exists
        if ($stageData->fields) {
            $this->stageFieldsService->fill($stage, $stageData->fields);
        }

        // save stage laboratory fields directly if exists
        if ($stageData->fieldsLaboratory) {
            $this->stageFieldsLaboratoryService->fill($stage, $stageData->fieldsLaboratory);
        }

        // save stage data
        if ($stageData->data) {
            $stage->data = array_merge($stage->data->toArray(), $stageData->data);
        }

        // save stage fields alternatively from bulk fields request if exists
        /*if ($collectedData->has('stages')) {
            $stages = $collectedData->get('stages');
            $this->stageFieldsService->fill($stage, $stages[$stage->id]['fields'] ?? []);
        }*/

        if (!$stage->save()) {
            return errorOperationResponse();
        }

        app(StageEmployeesService::class)->assignFreeEmployees($stage);
        app(ChatSyncService::class)->syncStage($stage);

        if ($stage->status !== $statusOld) {
            StageStatusChangedEvent::dispatch($stage, $statusOld);
        }

        return successOperationResponse([
            'stage' => $stage
        ]);
    }

    /**
     * Whether stage is ready for work.
     */
    public function isReadyForWork(Stage $stage): OperationResponse
    {
        $isReadyForWork = StageHelper::isReadyForWork($stage);

        if (!$isReadyForWork['ready']) {
            return errorOperationResponse(null, $isReadyForWork['errors']);
        }

        return successOperationResponse();
    }

    /**
     * Send stage to work.
     */
    public function sendToWork(Stage $stage): OperationResponse
    {
        $this->update($stage, StageData::from([
            'status' => StageStatusEnum::VERIFICATION->value,
        ]));

        return successOperationResponse([
            'stage' => $stage,
        ]);
    }

    /**
     * Save info about casts taking.
     */
    public function takeCasts(Stage $stage, StageData $stageData): OperationResponse
    {
        $this->stageRepository->update($stage, $stageData);

        app()->make(ChatQuickMessageService::class)->takeCasts($stage);

        $stage->order->doctor->clientManager->notify(new StageTakeCastsNotification($stage));

        return successOperationResponse([
            'stage' => $stage,
        ]);
    }

    /**
     * Update list of order stages
     */
    public function updateBulk(Collection $stagesData): OperationResponse
    {
        foreach ($stagesData as $stageData) {
            $stage = Stage::firstWhere('id', $stageData->id);

            $operationResponse = $this->update($stage, $stageData);

            if (!$operationResponse->isSuccess()) {
                return $operationResponse;
            }
        }

        return successOperationResponse();
    }

    public function applyGuaranteeFromProduction(Production $production): OperationResponse
    {
        if ($production->id === $production->check->productions->last()->id
            && $production->step_to === $production->check->steps_count
            && $production->delivery_status === ProductionDeliveryStatusEnum::DELIVERED->value
        ) {
            $production->check->stage->update([
                'guarantee_date' => now()->addDays(90)->addDays(($production->step_to - $production->step_from) * 14)
            ]);
        }

        return successOperationResponse();
    }

    public function linkAddress(Stage $stage, Address $address): void
    {
        $stage->update([
            'delivery_address_id' => $address->id,
        ]);
    }

    /**
     * Destroy model.
     */
    public function destroy(Stage $stage): OperationResponse
    {
        $stageService = $this;

        if (!$stage->delete()) {
            errorOperationResponse();
        }

        $this->fileService->destroyBulk($stage->photosAdditional);

        $this->fileService->destroy($stage->photoFrontal);
        $this->fileService->destroy($stage->photoFrontalWithEmma);
        $this->fileService->destroy($stage->photoFrontalWithOpenedMouth);
        $this->fileService->destroy($stage->photoFrontalWithSmile);
        $this->fileService->destroy($stage->photoLateralFromLeft);
        $this->fileService->destroy($stage->photoLateralFromRight);
        $this->fileService->destroy($stage->photoOcclusiveViewBottom);
        $this->fileService->destroy($stage->photoOcclusiveViewTop);
        $this->fileService->destroy($stage->photoOpg);
        $this->fileService->destroy($stage->photoToProfile);
        $this->fileService->destroy($stage->photoScanImpression);

        $stage->checks->each(function (Check $check) use ($stageService) {
            $stageService->checkService->destroy($check);
        });

        return successOperationResponse();
    }
}
