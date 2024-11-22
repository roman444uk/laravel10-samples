<?php

namespace App\Classes\Helpers\Db;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class PatientHelper
{
    public static function getSelectOptions(EloquentCollection $patients, string $emptyOption = null, string $keyBy = 'id'): Collection
    {
        $collection = $patients
            ->keyBy($keyBy)
            ->map(function (Patient $patient) {
                return $patient->full_name;
            });

        if ($emptyOption !== null) {
            $collection->prepend($emptyOption, '');
        }

        return collect($collection->toArray());
    }
}
