<?php

namespace App\Data;

use App\Data\Casts\ArrayFromJson;
use App\Models\Clinic;
use Spatie\LaravelData\Attributes\WithCast;

class ClinicData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?int    $id,
        public ?string $name,
        #[WithCast(ArrayFromJson::class)]
        public ?array  $data,
    )
    {
    }

    public static function fromModel(Clinic $clinic): self
    {
        return new self(
            $clinic->id,
            $clinic->data['name'],
            $clinic->data,
        );
    }
}
