<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageLateralCrossRatioEnum: string
{
    use EnumTrait;

    case ABSENT = 'absent';
    case DO_NOT_CHANGE = 'do-not-change';
    case ADJUST = 'adjust';
}
