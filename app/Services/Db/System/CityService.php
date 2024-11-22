<?php

namespace App\Services\Db\System;

use App\Classes\Helpers\Db\CityHelper;
use App\Models\Repositories\CityRepository;
use Illuminate\Support\Collection;

class CityService
{
    public function __construct(
        protected CityRepository $cityRepository,
    )
    {
    }

    /**
     * List of cities for dropdown list
     */
    public function getCitiesList(): Collection
    {
        return CityHelper::getSelectOptions(
            $this->cityRepository->list(), ''
        );
    }
}
