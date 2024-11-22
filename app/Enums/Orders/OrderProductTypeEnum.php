<?php

namespace App\Enums\Orders;

use App\Traits\EnumTrait;

enum OrderProductTypeEnum: string
{
    use EnumTrait;

    case ALIGNER = 'aligner';
    case RETAINER = 'retainer';
}
