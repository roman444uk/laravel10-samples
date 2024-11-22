<?php

namespace App\Classes\Helpers\Db;

use App\Models\Agreement;
use App\Models\Banner;
use App\Models\Characteristic;
use App\Models\City;
use App\Models\Lk;
use App\Models\Manager;
use App\Models\News;
use App\Models\Product;
use App\Models\ReclamationLetter;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class CityHelper
{
    /**
     * @param EloquentCollection $cities
     * @param string|null $emptyOption
     * @param string $keyBy
     *
     * @return Collection
     */
    public static function getSelectOptions(EloquentCollection $cities, string $emptyOption = null, string $keyBy = 'id'): Collection
    {
        $collection = $cities
            ->keyBy($keyBy)
            ->map(function (City $city) {
                return $city->name;
            });

        if ($emptyOption !== null) {
            $collection->prepend($emptyOption, '');
        }

        return collect($collection->toArray());
    }
}
