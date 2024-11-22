<?php

namespace App\Data;

use App\Data\Casts\ModelDataCollectionCast;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\WithCast;

class OrderData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?int                  $id,
        public ?int                  $doctorId,
        public ?int                  $patientId,
        public ?int                  $clinicId,
        public ?string               $productType,
        public ?Carbon               $createdAt,
        public ?Carbon               $updatedAt,
        public ?DoctorData           $doctor,
        public ?PatientData          $patient,
        public ?ClinicData           $clinic,
        #[WithCast(ModelDataCollectionCast::class, StageData::class)]
        public Collection|array|null $stages,
    )
    {
    }

    public static function fromModel(Order $order): self
    {
        return new self(
            $order->id,
            $order->doctor_id,
            $order->patient_id,
            $order->clinic_id,
            $order->product_type,
            $order->created_at,
            $order->updated_at,
            $order->whenLoaded('doctor', fn(Order $order) => DoctorData::fromModel($order->doctor)),
            $order->whenLoaded('patient', fn(Order $order) => PatientData::fromModel($order->patient)),
            $order->whenLoaded('clinic', fn(Order $order) => ClinicData::fromModel($order->clinic)),
            $order->whenLoaded('stages', fn(Order $order) => StageData::collect($order->stages)),
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::from([
            'id' => $request->validated('id'),
            'doctorId' => $request->validated('doctor_id'),
            'patientId' => $request->validated('patient_id'),
            'clinicId' => $request->validated('clinic_id'),
            'productType' => $request->validated('product_type'),
            'createdAt' => $request->validated('created_at'),
            'updatedAt' => $request->validated('updated_at'),
        ]);
    }
}
