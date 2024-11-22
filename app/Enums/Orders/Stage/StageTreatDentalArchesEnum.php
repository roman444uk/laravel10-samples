<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageTreatDentalArchesEnum: string
{
    use EnumTrait;

    case BOTH = 'both';
    case BOTTOM = 'bottom';
    case TOP = 'top';
}
