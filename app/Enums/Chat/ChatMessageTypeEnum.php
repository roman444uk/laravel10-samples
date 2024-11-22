<?php

namespace App\Enums\Chat;

use App\Traits\EnumTrait;

enum ChatMessageTypeEnum: string
{
    use EnumTrait;

    case USUAL = 'usual';
    case SYSTEM = 'system';
}
