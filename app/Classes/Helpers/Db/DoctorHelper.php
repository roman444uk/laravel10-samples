<?php

namespace App\Classes\Helpers\Db;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class DoctorHelper
{
    public static function getSelectOptions(EloquentCollection $doctors, string $emptyOption = null, string $keyBy = 'id'): Collection
    {
        $collection = $doctors
            ->keyBy($keyBy)
            ->map(function (Doctor $doctor) {
                return $doctor->user->name;
            });

        if ($emptyOption !== null) {
            $collection->prepend($emptyOption, '');
        }

        return collect($collection->toArray());
    }
}
