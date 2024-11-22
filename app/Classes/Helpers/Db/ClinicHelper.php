<?php

namespace App\Classes\Helpers\Db;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class ClinicHelper
{
    public static function getSelectOptions(EloquentCollection $clinics, string $emptyOption = null, string $keyBy = 'id'): Collection
    {
        $collection = $clinics
            ->keyBy($keyBy)
            ->map(function (Clinic $clinic) {
                return $clinic->data['data']['name']['short_with_opf'];
            });

        if ($emptyOption !== null) {
            $collection->prepend($emptyOption, '');
        }

        return collect($collection->toArray());
    }
}
