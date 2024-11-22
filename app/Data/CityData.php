<?php

namespace App\Data;

use App\Models\City;

class CityData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public int $id,
    )
    {
    }

    public static function fromModel(City $city): self
    {
        return new self(
            $city->id,
        );
    }
}
