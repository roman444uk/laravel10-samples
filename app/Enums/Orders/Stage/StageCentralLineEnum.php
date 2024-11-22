<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageCentralLineEnum: string
{
    use EnumTrait;

    case DO_NOT_TOUCH = 'do-not-touch';
    case MOVE_TO_LEFT = 'move-to-left';
    case MOVE_TO_RIGHT = 'move-to-right';
}
