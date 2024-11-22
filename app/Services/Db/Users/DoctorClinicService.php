<?php

namespace App\Services\Db\Users;

use App\Classes\ServicesResponses\OperationResponse;
use App\Data\ClinicData;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\DoctorClinic;
use App\Services\Db\System\ClinicService;

class DoctorClinicService
{
    public function __construct(
        protected ClinicService $clinicService
    )
    {
    }

    /**
     * Creates relation between doctor and clinic
     */
    public function firstOrCreate(Doctor $doctor, Clinic $clinic): DoctorClinic
    {
        return DoctorClinic::firstOrCreate([
            'doctor_id' => $doctor->id,
            'clinic_id' => $clinic->id,
        ]);
    }

    /**
     * Creates new doctor clinic relation with raw clinic data
     */
    public function createFromRawClinicData(Doctor $doctor, ClinicData $clinicData): OperationResponse
    {
        $clinic = $this->clinicService->findOrCreate($clinicData)->get('clinic');

        $doctorClinic = $this->firstOrCreate($doctor, $clinic);

        return successOperationResponse([
            'doctorClinic' => $doctorClinic
        ]);
    }
}
