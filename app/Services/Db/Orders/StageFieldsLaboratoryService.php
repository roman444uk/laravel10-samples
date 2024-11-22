<?php

namespace App\Services\Db\Orders;

class StageFieldsLaboratoryService extends StageFieldsBaseService
{
    protected string $fieldsProperty = 'fields_laboratory';

    protected array $_props = [
        'treatment_areas',
    ];

    /**
     * 1. Treatment areas
     */
    protected function fillTreatmentAreas(&$stageData, $requestData): void
    {
        $stageData['treatment_areas'] = [
            'top' => $this->getBool($requestData, 'treatment_areas.top'),
            'bottom' => $this->getBool($requestData, 'treatment_areas.bottom'),
        ];
    }
}
