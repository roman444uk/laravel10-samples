<?php

namespace App\Data\Filters;

use App\Traits\DataFilterTrait;
use Illuminate\Http\Request;

class OrderDataFilter extends \Spatie\LaravelData\Data
{
    use DataFilterTrait;

    public function __construct(
        public ?int    $id,
        public ?string $requesterRole,
        public ?int    $doctorId,
        public ?int    $patientId,
        public ?int    $clinicId,
        public ?int    $clientManagerId,
        public ?int    $clinicalSpecialistId,
        public ?int    $modeler3dId,
        public ?int    $technicianDigitizerId,
        public ?int    $technicianProductionId,
        public ?int    $logisticManagerId,
        public ?string $productType,
        public ?string $search,
        public ?int    $pageSize,
        public ?string $sort,
        public ?string $desc,
    )
    {
    }

    public static function fromRequest(Request $request, $defaultData = []): self
    {
        return self::from([
            'requesterRole' => $request->get('requester_role', $defaultData['requester_role'] ?? null),
            'doctorId' => $request->get('doctor_id', $defaultData['doctor_id'] ?? null),
            'clientManagerId' => $request->get('client_manager_id', $defaultData['client_manager_id'] ?? null),
            'clinicalSpecialistId' => $request->get('clinical_specialist_id', $defaultData['clinical_specialist_id'] ?? null),
            'modeler3dId' => $request->get('modeler_3d_id', $defaultData['modeler_3d_id'] ?? null),
            'technicianDigitizerId' => $request->get('technician_digitizer_id', $defaultData['technician_digitizer_id'] ?? null),
            'technicianProductionId' => $request->get('technician_production_id', $defaultData['technician_production_id'] ?? null),
            'logisticManagerId' => $request->get('logistic_manager_id', $defaultData['logistic_manager_id'] ?? null),
            'search' => $request->get('search'),
        ]);
    }

    public function clientManagerId(?int $clientManagerId): self
    {
        $this->clientManagerId = $clientManagerId;

        return $this;
    }

    public function clinicalSpecialistId(?int $clinicalSpecialistId): self
    {
        $this->clinicalSpecialistId = $clinicalSpecialistId;

        return $this;
    }

    public function doctorId(?int $doctorId): self
    {
        $this->doctorId = $doctorId;

        return $this;
    }
}
