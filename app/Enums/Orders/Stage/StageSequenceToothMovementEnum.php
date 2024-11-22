<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageSequenceToothMovementEnum: string
{
    use EnumTrait;

    case ACCORDING_LABORATORY = 'according-laboratory';
    case PREFERENCES = 'preferences';
}
