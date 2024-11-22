<?php

namespace App\Data;

use App\Models\Profile;
use Illuminate\Http\UploadedFile;

class ProfileData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?int          $id,
        public ?int          $userId,
        public ?string       $fullName,
        public ?string       $phone,
        public ?int          $cityId,
        public ?CityData     $city,
        public ?FileData     $fileAvatar,
        public ?UploadedFile $avatar
    )
    {
    }

    public static function fromModel(Profile $profile): self
    {
        return new self(
            $profile->id,
            $profile->user_id,
            $profile->full_name,
            $profile->phone,
            $profile->city_id,
            $profile->whenLoaded('city', fn(Profile $profile) => CityData::fromModel($profile->city)),
            $profile->whenLoaded('fileAvatar', fn(Profile $profile) => FileData::fromModel($profile->fileAvatar)),
            null,
        );
    }
}
