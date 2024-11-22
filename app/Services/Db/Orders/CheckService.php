<?php

namespace App\Services\Db\Orders;

use App\Classes\Helpers\Db\FileHelper;
use App\Classes\ServicesResponses\OperationResponse;
use App\Data\CheckData;
use App\Enums\Orders\CheckFileTypeEnum;
use App\Enums\Orders\CheckStatusEnum;
use App\Models\Check;
use App\Models\Production;
use App\Models\Repositories\CheckRepository;
use App\Models\Stage;
use App\Services\Db\System\FileService;

class CheckService
{
    public function __construct(
        protected CheckRepository         $checkRepository,
        protected FileService             $fileService,
        protected ProductionService       $productionService,
    )
    {
    }

    /**
     * Creates `Check` model.
     */
    public function store(CheckData $checkData, Stage $stage): OperationResponse
    {
        $latestCheck = Check::where(['stage_id' => $stage->id])->latest()->first();

        $checkData->stageId = $stage->id;
        $checkData->number = ($latestCheck?->number ?? 0) + 1;
        $checkData->status = CheckStatusEnum::NEW->value;

        $check = $this->checkRepository->create($checkData);

        if (!$check) {
            return errorOperationResponse();
        }

        return successOperationResponse([
            'check' => $check,
        ]);
    }

    /**
     * Updates an `Check` model.
     */
    public function update(Check $check, CheckData $checkData): OperationResponse
    {
        if (!$this->checkRepository->update($check, $checkData)) {
            return errorOperationResponse();
        }

        if ($checkData->fileSetupBottom) {
            $this->fileService->storeUploadedFile($checkData->fileSetupBottom, $check, CheckFileTypeEnum::SETUP_BOTTOM->value, getUserId());
        }

        if ($checkData->fileSetupTop) {
            $this->fileService->storeUploadedFile($checkData->fileSetupTop, $check, CheckFileTypeEnum::SETUP_TOP->value, getUserId());
        }

        return successOperationResponse([
            'check' => $check,
        ]);
    }

    /**
     * Destroys `Check` model.
     */
    public function destroy(Check $check): OperationResponse
    {
        $checkService = $this;

        if (!$check->delete()) {
            return errorOperationResponse();
        }

        $this->fileService->destroy($check->fileSetupTop);
        $this->fileService->destroy($check->fileSetupBottom);

        $check->productions->each(function (Production $production) use ($checkService) {
            $checkService->productionService->destroy($production);
        });

        return successOperationResponse();
    }

    public function applySetupsFilesNames(CheckData $checkData, Check $check, Stage $stage = null): CheckData
    {
        $stage = $stage ?? $check->stage;

        $checkData->fileSetupTopName = FileHelper::getUploadPathByOwner($check, CheckFileTypeEnum::SETUP_TOP->value)
            . '/' . $stage->order_id . '-' . $stage->id . '-' . $check->number . '-upper.zip';

        $checkData->fileSetupBottomName = FileHelper::getUploadPathByOwner($check, CheckFileTypeEnum::SETUP_BOTTOM->value)
            . '/' . $stage->order_id . '-' . $stage->id . '-' . $check->number . '-lower.zip';

        return $checkData;
    }
}
