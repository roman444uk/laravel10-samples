<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageSagittalIncisorRatioEnum: string
{
    use EnumTrait;

    case DO_NOT_CHANGE = 'do-not-change';
    case CHANGE = 'change';
}
