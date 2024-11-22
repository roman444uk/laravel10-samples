<?php

namespace App\Models\Repositories;

use App\Data\PatientData;
use App\Models\Patient;

class PatientRepository
{
    public function create(PatientData $patientData): ?Patient
    {
        return Patient::create([
            'doctor_id' => $patientData->doctorId,
            'full_name' => $patientData->fullName,
            'phone' => $patientData->phone,
            'email' => $patientData->email,
            'gender' => $patientData->gender,
            'birth_date' => $patientData->birthDate,
            'comment' => $patientData->comment,
        ]);
    }

    public function update(Patient $patient, PatientData $patientData): bool
    {
        $patient->fill([
            'doctor_id' => $patientData->doctorId,
            'full_name' => $patientData->fullName,
            'phone' => $patientData->phone,
            'email' => $patientData->email,
            'gender' => $patientData->gender,
            'birth_date' => $patientData->birthDate,
            'comment' => $patientData->comment,
        ]);

        return $patient->save();
    }
}
