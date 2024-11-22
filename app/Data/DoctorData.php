<?php

namespace App\Data;

use App\Data\Casts\ModelDataCollectionCast;
use App\Models\Doctor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\WithCast;

class DoctorData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?int                  $id,
        public ?int                  $userId,
        public ?int                  $clientManagerId,
        public ?string               $fullName,
        public ?string               $phone,
        public ?int                  $cityId,
        public ?string               $specialization,
        public ?string               $experienceFrom,
        public ?string               $experienceWithAligners,
        public ?Carbon               $createdAt,
        public ?Carbon               $updatedAt,
        public ?UserData             $clientManager,
        #[WithCast(ModelDataCollectionCast::class, ClinicData::class)]
        public Collection|array|null $clinics
    )
    {
    }

    public static function fromModel(Doctor $doctor): self
    {
        return new self(
            $doctor->id,
            $doctor->user_id,
            $doctor->client_manager_id,
            $doctor->full_name,
            $doctor->phone,
            $doctor->city_id,
            $doctor->specialization,
            $doctor->experience_from,
            $doctor->experience_with_aligners,
            $doctor->created_at,
            $doctor->updated_at,
            $doctor->whenLoaded('clientManager', fn(Doctor $doctor) => UserData::fromModel($doctor->clientManager)),
            $doctor->doctorClinics,
        );
    }
}
