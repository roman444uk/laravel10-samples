<?php

namespace App\Enums\Chat;

use App\Traits\EnumTrait;

enum ChatMessageFileTypeEnum: string
{
    use EnumTrait;

    case FILE = 'file';
}
