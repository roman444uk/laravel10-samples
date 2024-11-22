<?php

namespace App\Services\Db\Users;

use App\Classes\ServicesResponses\OperationResponse;
use App\Data\DoctorData;
use App\Data\ProfileData;
use App\Data\UserData;
use App\Enums\Users\ProfileFileTypeEnum;
use App\Enums\Users\UserRoleEnum;
use App\Models\Doctor;
use App\Models\Profile;
use App\Models\Repositories\ProfileRepository;
use App\Models\Repositories\UserRepository;
use App\Models\User;
use App\Services\Db\System\ClinicService;
use App\Services\Db\System\FileService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected ClinicService       $clinicService,
        protected DoctorClinicService $doctorClinicService,
        protected FileService         $fileService,
        protected ProfileRepository   $profileRepository,
        protected UserRepository      $userRepository,
    )
    {
    }

    /**
     * Creates new `User` record.
     */
    public function store(UserData $userData, string $role): OperationResponse
    {
        $userData->role = $role;
        $userData->name = $userData->name ?? $userData->profile->fullName;

        $user = $this->userRepository->create($userData);

        if (!$user || !$user->wasRecentlyCreated) {
            return errorOperationResponse();
        }

        $userData->profile->userId = $user->id;
        $userData->profile->fullName = $userData->profile->fullName ?? $userData->name;

        $profile = $this->profileRepository->create($userData->profile);

        if (!$profile || !$profile->wasRecentlyCreated) {
            return errorOperationResponse();
        }

        $user->syncRoles($user->role);

        $this->updateOrCreateRoleModel($user, $userData);
        $this->saveAvatar($profile, $userData->profile);

        event(new Registered($user));

        return successOperationResponse([
            'user' => $user
        ]);
    }

    /**
     * Updates `User` record.
     */
    public function update(User $user, UserData $userData): OperationResponse
    {
        $userData->name = $userData->name ?? $userData->profile->fullName;

        if (!$this->userRepository->update($user, $userData)) {
            return errorOperationResponse();
        }

        $userData->profile->fullName = $userData->profile->fullName ?? $userData->name;

        if (!$this->profileRepository->update($user->profile, $userData->profile)) {
            return errorOperationResponse();
        }

        $user->syncRoles($user->role);

        $this->updateOrCreateRoleModel($user, $userData);
        $this->saveAvatar($user->profile, $userData->profile);

        return successOperationResponse([
            'user' => $user
        ]);
    }

    /**
     * Update user password.
     */
    public function updatePassword(User $user, string $password, array $addAttributes = []): OperationResponse
    {
        $user->update(array_merge(['password' => Hash::make($password)], $addAttributes));

        return successOperationResponse();
    }

    /**
     * Saves related role model if this model supposed to be.
     */
    public function updateOrCreateRoleModel(User $user, UserData $userData): void
    {
        $roleService = match ($user->role) {
            UserRoleEnum::DOCTOR->value => App::make(DoctorService::class),
            default => null,
        };

        if ($roleService instanceof DoctorService) {
            $doctorData = $userData->doctor ?? DoctorData::fromModel(new Doctor());

            if ($doctorData) {
                $user->doctor = $roleService->updateOrCreate($user, $doctorData)->get('doctor');
            }

            $roleService->assignFreeClientManager($user->doctor);
        }
    }

    /**
     * Saves profile avatar photo.
     */
    public function saveAvatar(Profile $profile, ProfileData $profileData): void
    {
        if ($profileData->avatar) {
            $currentAvatar = $profile->fileAvatar;

            $operationResponse = $this->fileService->storeUploadedFile(
                $profileData->avatar, $profile, ProfileFileTypeEnum::AVATAR->value
            );

            if ($operationResponse->isSuccess() && $currentAvatar) {
                $this->fileService->destroy($currentAvatar);
            }
        }
    }
}
