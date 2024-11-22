<?php

namespace App\Models\Repositories;

use App\Data\ProfileData;
use App\Models\Profile;

class ProfileRepository
{
    public function create(ProfileData $profileData): ?Profile
    {
        return Profile::create([
            'user_id' => $profileData->userId,
            'full_name' => $profileData->fullName,
            'phone' => $profileData->phone,
            'city_id' => $profileData->cityId,
        ]);
    }

    public function update(Profile $profile, ProfileData $profileData): bool
    {
        $profile->fill([
            'full_name' => $profileData->fullName,
            'phone' => $profileData->phone,
            'city_id' => $profileData->cityId,
        ]);

        return $profile->save();
    }
}
