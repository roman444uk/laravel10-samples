<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageWideningDueToEnum: string
{
    use EnumTrait;

    case BODY_WISE = 'body-wise';
    case TORQUE = 'torque';
}
