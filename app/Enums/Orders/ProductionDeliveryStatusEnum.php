<?php

namespace App\Enums\Orders;

use App\Traits\EnumTrait;

enum ProductionDeliveryStatusEnum: string
{
    use EnumTrait;

    case DELIVERED = 'delivered';
    case IN_DELIVERY = 'in_delivery';
    case NEW = 'new';
}
