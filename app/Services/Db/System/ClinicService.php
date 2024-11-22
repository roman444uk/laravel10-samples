<?php

namespace App\Services\Db\System;

use App\Classes\ServicesResponses\OperationResponse;
use App\Data\ClinicData;
use App\Models\Clinic;

class ClinicService
{
    /**
     * Creates new clinic record.
     */
    public function findOrCreate(ClinicData $clinicData): OperationResponse
    {
        $clinic = Clinic::firstWhere([
            'data->data->inn' => $clinicData->data['data']['inn'],
        ]);

        if (!$clinic) {
            $clinic = Clinic::create([
                'data' => $clinicData->data
            ]);
        }

        if (!$clinic) {
            return errorOperationResponse();
        }

        return successOperationResponse([
            'clinic' => $clinic
        ]);
    }
}
