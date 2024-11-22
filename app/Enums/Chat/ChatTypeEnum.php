<?php

namespace App\Enums\Chat;

use App\Traits\EnumTrait;

enum ChatTypeEnum: string
{
    use EnumTrait;

    case INTERNAL = 'internal';
    case WITH_DOCTOR = 'with-doctor';

    public static function casesForOrder(): array
    {
        return [
            self::INTERNAL,
            self::WITH_DOCTOR,
        ];
    }
}
