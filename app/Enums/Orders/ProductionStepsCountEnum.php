<?php

namespace App\Enums\Orders;

use App\Traits\EnumTrait;

enum ProductionStepsCountEnum: string
{
    use EnumTrait;

    case FIVE = '5';

    case TEN = '10';

//    case ALL = 'all';

    case NEED_MORE = 'need-more';
}
