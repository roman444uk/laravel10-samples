<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageAlignersTrimmingEnum: string
{
    use EnumTrait;

    case HIGH = 'high';
    case COMBINED = 'combined';
    case LOW = 'low';
}
