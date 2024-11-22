<?php

namespace App\Data;

use App\Classes\Helpers\Db\ProductionHelper;
use App\Models\Production;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductionData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?int            $id,
        public ?int            $checkId,
        public ?string         $status,
        public ?int            $phase,
        public string|int|null $stepsCount,
        public ?int            $stepFrom,
        public ?int            $stepTo,
        public ?int            $productionTerm,
        public ?string         $deliveryAddressId,
        public ?bool           $deliveryAddressConfirmed,
        public ?string         $deliveryStatus,
        public ?Carbon         $createdAt,
        public ?Carbon         $updatedAt,
        public ?CheckData      $check,
        public ?bool           $canChangeProductionTerm,
    )
    {
    }

    public static function fromModel(Production $production): self
    {
        return new self(
            $production->id,
            $production->check_id,
            $production->status,
            $production->phase,
            ProductionHelper::getStepsCount($production),
            $production->step_from,
            $production->step_to,
            $production->production_term,
            $production->delivery_address_id,
            $production->delivery_address_confirmed,
            $production->delivery_status,
            $production->created_at,
            $production->updated_at,
            $production->whenLoaded('check', fn(Production $production) => CheckData::fromModel($production->check)),
            can('changeProductionTerm', $production),
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::from([
            'checkId' => $request->validated('check_id'),
            'status' => $request->validated('status'),
            'stepsCount' => $request->validated('steps_count'),
            'stepFrom' => $request->validated('step_from'),
            'stepTo' => $request->validated('step_to'),
            'productionTerm' => $request->validated('production_term'),
            'deliveryAddressId' => $request->validated('delivery_address_id'),
            'deliveryAddressConfirmed' => $request->validated('delivery_address_confirmed'),
            'deliveryStatus' => $request->validated('delivery_status'),
        ]);
    }
}
