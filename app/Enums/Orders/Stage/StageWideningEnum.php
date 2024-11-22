<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageWideningEnum: string
{
    use EnumTrait;

    case DO_NOT_DO = 'do-not-do';
    case THREE_THREE = 'three-three';
    case FIVE_FIVE = 'five-five';
    case SIX_SIX = 'six-six';
    case SEVEN_SEVEN = 'seven-seven';
}
