<?php

namespace App\Models\Repositories;

use App\Data\Filters\UserDataFilter;
use App\Data\UserData;
use App\Models\Builders\UserBuilder;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function create(UserData $userData): ?User
    {
        return User::create([
            'name' => $userData->name,
            'email' => $userData->email,
            'password' => Hash::make($userData->password),
            'role' => $userData->role,
            'first_login_new_password' => $userData->firstLoginNewPassword ?? false,
        ]);
    }

    public function update(User $user, UserData $userData): bool
    {
        $user->fill([
            'name' => $userData->name,
            'email' => $userData->email,
            'role' => $userData->role ?? $user->role,
            'first_login_new_password' => $userData->firstLoginNewPassword ?? false,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        return $user->save();
    }

    /**
     * Return the most free client manager.
     */
    public function getFreeClientManager(): ?User
    {
        return User::isClientManager()
            ->withCount('clientManagerDoctors')
            ->orderBy('client_manager_doctors_count')
            ->first();
    }

    /**
     * Return the most free clinical specialist.
     */
    public function getFreeClinicalSpecialist(): ?User
    {
        return User::isClinicalSpecialist()
            ->withCount('clinicalSpecialistStages')
            ->orderBy('clinical_specialist_stages_count')
            ->first();
    }

    /**
     * Return the most free 3D modeler.
     */
    public function getFreeModeler3d(): ?User
    {
        return User::isModeler3d()
            ->withCount('modeler3dStages')
            ->orderBy('modeler3d_stages_count')
            ->first();
    }

    /**
     * Return the most free technician digitizer.
     */
    public function getFreeTechnicianDigitizer(): ?User
    {
        return User::isTechnicianDigitizer()
            ->withCount('technicianDigitizerStages')
            ->orderBy('technician_digitizer_stages_count')
            ->first();
    }

    /**
     * Return the most free technician production.
     */
    public function getFreeTechnicianProduction(): ?User
    {
        return User::isTechnicianProduction()
            ->withCount('technicianProductionStages')
            ->orderBy('technician_production_stages_count')
            ->first();
    }

    /**
     * Return the most free logistic manager.
     */
    public function getFreeLogisticManager(): ?User
    {
        return User::isLogisticManager()
            ->withCount('logisticManagerStages')
            ->orderBy('logistic_manager_stages_count')
            ->first();
    }

    public function paginate(UserDataFilter $userFilter): LengthAwarePaginator
    {
        /** @var UserBuilder $builder */
        $builder = User::with('profile');

        return $builder
            ->select('users.*')
            ->leftJoinDoctors()
            ->role($userFilter->role)
            ->clientManagerId($userFilter->clientManagerId)
            ->search($userFilter->search)
            ->orderBy('created_at', 'desc')
            ->paginate($userFilter->pageSize);
    }
}
