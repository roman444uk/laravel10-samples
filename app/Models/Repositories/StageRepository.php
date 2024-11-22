<?php

namespace App\Models\Repositories;

use App\Data\StageData;
use App\Models\Stage;

class StageRepository
{
    public function create(StageData $stageData): ?Stage
    {
        return Stage::create([
            'order_id' => $stageData->orderId,
            'type' => $stageData->type,
            'status' => $stageData->status,
            'number' => $stageData->number,
        ]);
    }

    public function update(Stage $stage, StageData $stageData): bool
    {
        $stage->fill([
            'order_id' => $stageData->orderId ?? $stage->order_id,
            'clinical_specialist_id' => $stageData->clinicalSpecialistId ?? $stage->clinical_specialist_id,
            'modeler_3d_id' => $stageData->modeler3dId ?? $stage->modeler_3d_id,
            'technician_digitizer_id' => $stageData->technicianDigitizerId ?? $stage->technician_digitizer_id,
            'technician_production_id' => $stageData->technicianProductionId ?? $stage->technician_production_id,
            'logistic_manager_id' => $stageData->logisticManagerId ?? $stage->logistic_manager_id,
            'type' => $stageData->type ?? $stage->type,
            'status' => $stageData->status ?? $stage->status,
            'number' => $stageData->number ?? $stage->number,
            'guarantee_date' => $stageData->guaranteeDate ?? $stage->guarantee_date,
            'delivery_address_id' => $stageData->deliveryAddressId ?? $stage->delivery_address_id,
            'take_casts_address_id' => $stageData->takeCastsAddressId ?? $stage->take_casts_address_id,
            'take_casts_date' => $stageData->takeCastsDate ?? $stage->take_casts_date,
            'take_casts_time' => $stageData->takeCastsTime ?? $stage->take_casts_time,
            'fields_text' => $stageData->fieldsText ?? $stage->fields_text,
            'fields_laboratory' => $stageData->fieldsLaboratory ?? $stage->fields_laboratory,
            'fields_type' => $stageData->fieldsType ?? $stage->fields_type,
        ]);

        return $stage->save();
    }
}
