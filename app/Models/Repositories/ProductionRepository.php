<?php

namespace App\Models\Repositories;

use App\Data\ProductionData;
use App\Models\Production;

class ProductionRepository
{
    public function create(ProductionData $productionData): ?Production
    {
        return Production::create([
            'check_id' => $productionData->checkId,
            'status' => $productionData->status,
            'phase' => $productionData->phase,
            'step_from' => $productionData->stepFrom,
            'step_to' => $productionData->stepTo,
            'production_term' => $productionData->productionTerm,
            'delivery_address_id' => $productionData->deliveryAddressId,
            'delivery_status' => $productionData->deliveryStatus,
        ]);
    }

    public function update(Production $production, ProductionData $productionData): bool
    {
        $production->fill([
            'status' => $productionData->status ?? $production->status,
            'step_from' => $productionData->stepFrom ?? $production->step_from,
            'step_to' => $productionData->stepTo ?? $production->step_to,
            'production_term' => $productionData->productionTerm ?? $production->production_term,
            'delivery_address_id' => $productionData->deliveryAddressId ?? $production->delivery_address_id,
            'delivery_address_confirmed' => $productionData->deliveryAddressConfirmed ?? $production->delivery_address_confirmed,
            'delivery_status' => $productionData->deliveryStatus ?? $production->delivery_status,
        ]);

        return $production->save();
    }
}
