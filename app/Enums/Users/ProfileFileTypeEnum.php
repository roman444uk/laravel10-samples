<?php

namespace App\Enums\Users;

use App\Traits\EnumTrait;

enum ProfileFileTypeEnum: string
{
    use EnumTrait;

    case AVATAR = 'avatar';
}
