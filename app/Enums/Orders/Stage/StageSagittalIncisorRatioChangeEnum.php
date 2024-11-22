<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageSagittalIncisorRatioChangeEnum: string
{
    use EnumTrait;

    case BODY_WISE = 'body-wise';
    case TORQUE = 'torque';
}
