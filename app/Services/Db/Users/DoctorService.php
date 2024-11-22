<?php

namespace App\Services\Db\Users;

use App\Classes\ServicesResponses\OperationResponse;
use App\Classes\ServicesResponses\OperationResponseError;
use App\Data\ClinicData;
use App\Data\DoctorData;
use App\Models\Doctor;
use App\Models\DoctorClinic;
use App\Models\Repositories\DoctorRepository;
use App\Models\Repositories\UserRepository;
use App\Models\User;
use App\Services\Db\System\ClinicService;
use Illuminate\Support\Collection;

class DoctorService
{
    public function __construct(
        protected ClinicService       $clinicService,
        protected DoctorClinicService $doctorClinicService,
        protected DoctorRepository    $doctorRepository,
        protected UserRepository      $userRepository,
    )
    {
    }

    /**
     * Updates or creates new doctor record.
     */
    public function updateOrCreate(User $user, DoctorData $doctorData): OperationResponse
    {
        $doctor = $this->doctorRepository->updateOrCreate($user, $doctorData);

        $this->assignFreeClientManager($doctor);

        if (!$doctor) {
            return new OperationResponseError();
        }

        $this->saveClinics($doctorData->clinics ?? collect(), $doctor);

        return successOperationResponse([
            'user' => $user,
            'doctor' => $doctor,
        ]);
    }

    /**
     * Updates related doctor clinics
     */
    public function saveClinics(Collection $clinicsData, Doctor $doctor): void
    {
        // Remove unnecessary clinics relations
        $stillExists = [];
        collect($clinicsData)->each(function (ClinicData $clinicData) use (&$stillExists) {
            $stillExists[] = $clinicData->id;
        });
        $stillExists = array_filter($stillExists);

        DoctorClinic::where('doctor_id', $doctor->id)->whereNotIn('id', $stillExists ?: [0])->delete();

        // Save new clinics relations
        foreach ($clinicsData as $clinicData) {
            if (!empty($clinicData->id) || empty($clinicData->data)) {
                continue;
            }

            $clinic = $this->clinicService->findOrCreate($clinicData)->get('clinic');

            DoctorClinic::firstOrCreate([
                'doctor_id' => $doctor->id,
                'clinic_id' => $clinic->id,
            ]);
        }
    }

    /**
     * Assigns client manager to doctor if it hasn't been assigned yet.
     */
    public function assignFreeClientManager(Doctor $doctor): void
    {
        if (!$doctor->client_manager_id) {
            $doctor->update([
                'client_manager_id' => $this->userRepository->getFreeClientManager()?->id
            ]);
        }
    }
}
