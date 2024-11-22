<?php

namespace App\Classes\Helpers\Db;

use App\Models\User;
use App\Support\Str;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class UserHelper
{
    public static function getFullName(User $user): ?string
    {
        /*if (isUserDoctor($user)) {
            return DoctorHelper::getFullName($user->doctor);
        }*/

        return $user->name;
    }

    public static function getRoleLabel(string $role): string
    {
        return __('users.role_enums.' . Str::delimiterFromDashToUnderscore($role));
    }

    public static function getSelectOptions(EloquentCollection $users, string $emptyOption = null, string $keyBy = 'id'): Collection
    {
        $collection = $users
            ->keyBy($keyBy)
            ->map(function (User $user) {
                return $user->name;
            });

        if ($emptyOption !== null) {
            $collection->prepend($emptyOption, '');
        }

        return collect($collection->toArray());
    }
}
