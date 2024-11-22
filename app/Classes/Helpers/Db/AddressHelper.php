<?php

namespace App\Classes\Helpers\Db;

use App\Models\Address;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class AddressHelper
{
    public static function getSelectOptions(EloquentCollection $patients, string $emptyOption = null, string $keyBy = 'id'): Collection
    {
        $collection = $patients
            ->keyBy($keyBy)
            ->map(function (Address $address) {
                return $address->address;
            });

        if ($emptyOption !== null) {
            $collection->prepend($emptyOption, '');
        }

        return collect($collection->toArray());
    }
}
