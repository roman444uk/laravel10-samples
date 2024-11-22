<?php

namespace App\Enums\Orders;

use App\Traits\EnumTrait;

enum CheckFileTypeEnum: string
{
    use EnumTrait;

    case SETUP_BOTTOM = 'setup-bottom';
    case SETUP_TOP = 'setup-top';
}
