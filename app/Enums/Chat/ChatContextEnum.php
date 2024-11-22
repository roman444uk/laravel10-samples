<?php

namespace App\Enums\Chat;

use App\Traits\EnumTrait;

enum ChatContextEnum: string
{
    use EnumTrait;

    case ORDER = 'order';
    case STAGE = 'stage';
}
