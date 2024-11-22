<?php

namespace App\Data;

use App\Classes\Helpers\Db\FileHelper;
use App\Data\Casts\ModelDataCast;
use App\Models\User;
use App\Support\Str;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\WithCast;

class UserData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?int         $id,
        public ?string      $avatarUrl,
        public string       $email,
        public ?string      $name,
        public ?string      $role,
        public ?string      $roleLabel,
        public ?string      $password,
        public ?bool        $firstLoginNewPassword,
        #[WithCast(ModelDataCast::class, ProfileData::class)]
        public ?ProfileData $profile,
        #[WithCast(ModelDataCast::class, DoctorData::class)]
        public ?DoctorData  $doctor,
    )
    {
    }

    public static function fromModel(User $user): self
    {
        return new self(
            $user->id,
            $user->profile->fileAvatar ? FileHelper::getFileUrl($user->profile->fileAvatar) : null,
            $user->email,
            $user->name,
            $user->role,
            __('users.role_enums.' . Str::delimiterFromDashToUnderscore($user->role)),
            null,
            $user->first_login_new_password,
            $user->whenLoaded('profile', fn(User $user) => ProfileData::fromModel($user->profile)),
            $user->whenLoaded('doctor', fn(User $user) => DoctorData::fromModel($user->doctor)),
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::from([
            'id' => $request->validated('id'),
            'avatarUrl' => $request->validated('avatar_url'),
            'email' => $request->validated('email'),
            'name' => $request->validated('name'),
            'role' => $request->validated('role'),
            'firstLoginNewPassword' => (bool)$request->validated('first_login_new_password'),
            'profile' => [
                'avatar' => $request->file('profile.avatar'),
                'fullName' => $request->validated('profile.full_name'),
                'phone' => $request->validated('profile.phone'),
                'cityId' => $request->validated('profile.city_id'),
            ],
            'doctor' => $request->validated('doctor', null) ? [
                'experienceFrom' => $request->validated('doctor.experience_from'),
                'experienceWithAligners' => $request->validated('doctor.experience_with_aligners'),
                'clinics' => $request->validated('doctor.clinics'),
            ] : null,
        ]);
    }
}
