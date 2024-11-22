<?php

namespace App\Enums\Orders;

use App\Traits\EnumTrait;

enum StageTabEnum: string
{
    use EnumTrait;

    case CHECK = 'check';
    case DELIVERIES = 'deliveries';
    case DELIVERY_ADDRESS = 'delivery-address';
    case PAYMENT = 'payment';
    case PHOTO = 'photo';
    case PRODUCTION_PARAMETERS = 'production-parameters';
    case SCANS_AND_IMPRESSIONS = 'scans-and-impressions';
    case TREATMENT_PLAN = 'treatment-plan';
    case X_RAY_AND_CT = 'x-ray-and-ct';

    public static function sorted(): array
    {
        return [
            self::TREATMENT_PLAN,
            self::PHOTO,
            self::X_RAY_AND_CT,
            self::SCANS_AND_IMPRESSIONS,
            self::CHECK,
            self::PRODUCTION_PARAMETERS,
            self::DELIVERY_ADDRESS,
            self::PAYMENT,
            self::DELIVERIES,
        ];
    }
}
