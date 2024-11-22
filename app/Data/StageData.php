<?php

namespace App\Data;

use App\Classes\Helpers\Db\StageHelper;
use App\Models\Stage;
use App\Support\Str;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StageData extends BaseData
{
    public function __construct(
        public ?int                   $id,
        public ?int                   $orderId,
        public ?int                   $clinicalSpecialistId,
        public ?int                   $modeler3dId,
        public ?int                   $technicianDigitizerId,
        public ?int                   $technicianProductionId,
        public ?int                   $logisticManagerId,
        public ?string                $type,
        public ?string                $status,
        public ?int                   $number,
        public ?Carbon                $guaranteeDate,
        public ?string                $deliveryAddressId,
        public ?string                $takeCastsAddressId,
        public Carbon|string|null     $takeCastsDate,
        public Carbon|string|null     $takeCastsTime,
        public ?array                 $fields,
        public ?string                $fieldsText,
        public ?array                 $fieldsLaboratory,
        public ?string                $fieldsType,
        public ArrayObject|array|null $data,
        public ?bool                  $isAvailableForEdition,
        public ?Carbon                $createdAt,
        public ?Carbon                $updatedAt,
        public ?UserData              $clinicalSpecialist,
        public ?UserData              $modeler3d,
        public ?UserData              $technicianDigitizer,
        public ?UserData              $technicianProduction,
        public ?UserData              $logisticManager,
    )
    {
    }

    public static function fromModel(Stage $stage): self
    {
        return new self(
            $stage->id,
            $stage->order_id,
            $stage->clinical_specialist_id,
            $stage->modeler_3d_id,
            $stage->technician_digitizer_id,
            $stage->technician_production_id,
            $stage->logistic_manager_id,
            $stage->type,
            $stage->status,
            $stage->number,
            $stage->guarantee_date,
            $stage->delivery_address_id,
            $stage->take_casts_address_id,
            $stage->take_casts_date,
            $stage->take_casts_time,
            $stage->fields,
            $stage->fields_text,
            $stage->fields_laboratory,
            $stage->fields_type,
            $stage->data,
            StageHelper::isAvailableForEdition($stage),
            $stage->created_at,
            $stage->updated_at,
            $stage->whenLoaded('clinicalSpecialist', fn(Stage $stage) => UserData::fromModel($stage->clinicalSpecialist)),
            $stage->whenLoaded('modeler3d', fn(Stage $stage) => UserData::fromModel($stage->modeler3d)),
            $stage->whenLoaded('technicianDigitizer', fn(Stage $stage) => UserData::fromModel($stage->technicianDigitizer)),
            $stage->whenLoaded('technicianProduction', fn(Stage $stage) => UserData::fromModel($stage->technicianProduction)),
            $stage->whenLoaded('logisticManager', fn(Stage $stage) => UserData::fromModel($stage->logisticManager)),
        );
    }

    public static function fromRequest(Request $request): self
    {
        $stage = [];
        if ($request->has('stages')) {
            $stages = $request->validated('stages');
            $stageRaw = array_shift($stages);

            $stage = [];
            collect($stageRaw)->each(function (mixed $data, string $key) use (&$stage) {
                $stage[Str::toCamelCase($key, '_')] = $data;
            });
        }

        $self = self::from(array_merge_recursive([
            'id' => $request->validated('id'),
            'orderId' => $request->validated('order_id'),
            'clinicalSpecialistId' => $request->validated('clinical_specialist_id'),
            'modeler3dId' => $request->validated('modeler3d_id'),
            'technicianDigitizerId' => $request->validated('technician_digitizer_id'),
            'technicianProductionId' => $request->validated('technician_production_id'),
            'logisticManagerId' => $request->validated('logistic_manager_id'),
            'type' => $request->validated('type'),
            'status' => $request->validated('status'),
            'number' => $request->validated('number'),
            'guaranteeDate' => $request->validated('guarantee_date'),
            'data' => $request->validated('data'),
            'deliveryAddressId' => $request->validated('delivery_address_id'),
            'takeCastsAddressId' => $request->validated('take_casts_address_id'),
            'takeCastsDate' => $request->validated('take_casts_date')
                ? Carbon::createFromFormat('Y-m-d', $request->validated('take_casts_date')) : null,
            'takeCastsTime' => $request->validated('take_casts_time')
                ? Carbon::createFromFormat('H:i:s', $request->validated('take_casts_time')) : null,
            'fields' => $request->get('fields'),
            'fieldsText' => $request->get('fields_text'),
            'fieldsLaboratory' => $request->get('fields_laboratory'),
            'fieldsType' => $request->get('fields_type'),
        ], $stage), $request);

        return $self;
    }
}
