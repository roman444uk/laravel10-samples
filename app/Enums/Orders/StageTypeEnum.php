<?php

namespace App\Enums\Orders;

use App\Traits\EnumTrait;

enum StageTypeEnum: string
{
    use EnumTrait;

    case CORRECTION = 'correction';
    case TREATMENT = 'treatment';
}
