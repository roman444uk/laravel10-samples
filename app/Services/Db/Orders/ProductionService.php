<?php

namespace App\Services\Db\Orders;

use App\Classes\ServicesResponses\OperationResponse;
use App\Data\ProductionData;
use App\Data\StageData;
use App\Enums\Orders\ProductionDeliveryStatusEnum;
use App\Enums\Orders\ProductionStatusEnum;
use App\Enums\Orders\ProductionStepsCountEnum;
use App\Enums\Orders\StageStatusEnum;
use App\Models\Address;
use App\Models\Check;
use App\Models\Production;
use App\Models\Repositories\ProductionRepository;
use App\Models\Stage;
use App\Services\Db\Chat\ChatMessageService;
use App\Services\Db\Chat\ChatQuickMessageService;

class ProductionService
{
    public function __construct(
        protected ChatMessageService   $chatMessageService,
        protected ProductionRepository $productionRepository,
    )
    {
    }

    /**
     * Creates new `Production` model.
     */
    public function store(ProductionData $productionData, Check $check): OperationResponse
    {
        $productionData->checkId = $check->id;
        $productionData->status = ProductionStatusEnum::NEW->value;
        $productionData->phase = $check->latestProduction ? $check->latestProduction->phase + 1 : 1;
        $productionData->deliveryAddressId = $check->stage->delivery_address_id;
        $productionData->deliveryStatus = ProductionDeliveryStatusEnum::NEW->value;

        $production = $this->productionRepository->create($productionData);

        if (!$production) {
            return errorOperationResponse();
        }

        if ($productionData->stepsCount === ProductionStepsCountEnum::NEED_MORE->value) {
            app(ChatQuickMessageService::class)->productionNeedMoreSteps($production);
        }

        app(StageLifeCycleService::class)->syncStatusWithProduction($production);

        return successOperationResponse([
            'production' => $production,
        ]);
    }

    /**
     * Updates an `Production` model.
     */
    public function update(Production $production, ProductionData $productionData): OperationResponse
    {
        $hasDeliveryStatusChanged = $productionData->deliveryStatus && $productionData->deliveryStatus !== $production->delivery_status;

        if (!$this->productionRepository->update($production, $productionData)) {
            return errorOperationResponse();
        }

        if ($hasDeliveryStatusChanged) {
            app(StageService::class)->applyGuaranteeFromProduction($production);
        }

        app(StageLifeCycleService::class)->syncStatusWithProduction($production);

        return successOperationResponse([
            'production' => $production,
        ]);
    }

    /**
     * Send production to work.
     */
    public function sendToWork(Production $production): OperationResponse
    {
        $this->update($production, ProductionData::from([
            'status' => ProductionStatusEnum::PRODUCTION->value,
        ]));

        return successOperationResponse([
            'production' => $production,
        ]);
    }

    /**
     * Destroys `Production` model.
     */
    public function destroy(Production $production): OperationResponse
    {
        if (!$production->delete()) {
            return errorOperationResponse();
        }

        return successOperationResponse();
    }

    public function linkAddress(Production $production, Address $address): void
    {
        $production->update([
            'delivery_address_id' => $address->id,
        ]);
    }
}
