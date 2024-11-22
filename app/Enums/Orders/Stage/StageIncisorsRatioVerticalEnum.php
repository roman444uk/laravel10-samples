<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageIncisorsRatioVerticalEnum: string
{
    use EnumTrait;

    case DEEP = 'deep';
    case NEUTRAL = 'neutral';
    case OPENED = 'opened';
    case STRAIGHT = 'straight';
}
