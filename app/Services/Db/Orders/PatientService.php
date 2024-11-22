<?php

namespace App\Services\Db\Orders;

use App\Classes\ServicesResponses\OperationResponse;
use App\Data\PatientData;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Repositories\PatientRepository;

class PatientService
{
    public function __construct(
        protected PatientRepository $patientRepository,
    )
    {
    }

    /**
     * Creates `Patient` model.
     */
    public function store(PatientData $patientData, Doctor $doctor): OperationResponse
    {
        $patientData->doctorId = $doctor->id;

        $patient = $this->patientRepository->create($patientData);

        if (!$patient || !$patient->wasRecentlyCreated) {
            return errorOperationResponse();
        }

        return successOperationResponse([
            'patient' => $patient
        ]);
    }

    /**
     * Updates `Patient` model.
     */
    public function update(Patient $patient, PatientData $patientData): OperationResponse
    {
        $patientData->doctorId = $patientData->doctorId ?? $patient->doctor_id;

        if (!$this->patientRepository->update($patient, $patientData)) {
            return errorOperationResponse();
        }

        return successOperationResponse([
            'patient' => $patient
        ]);
    }
}
