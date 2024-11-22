<?php

namespace App\Enums\Orders;

use App\Traits\EnumTrait;

enum ProductionStatusEnum: string
{
    use EnumTrait;

    case DELIVERED = 'delivered';
    case NEW = 'new';
    case PRODUCTION = 'production';
}
