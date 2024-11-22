<?php

namespace App\Classes\Helpers\Db;

use App\Enums\Orders\ProductionDeliveryStatusEnum;
use App\Models\Production;
use Illuminate\Support\Collection;


class ProductionHelper
{
    public static function getStepsCount(Production $production): int
    {
        return $production->step_to > $production->step_from ? $production->step_to - $production->step_from + 1  :0;
    }

    public static function getStatusColor(string $status = null): array|string
    {
        $colors = [
            ProductionDeliveryStatusEnum::DELIVERED->value => 'green',
            ProductionDeliveryStatusEnum::IN_DELIVERY->value => 'blue',
            ProductionDeliveryStatusEnum::NEW->value => 'blue',
        ];

        return $status ? $colors[$status] : $colors;
    }

    public static function deliveryStatusDropdownBadgeOptions(): Collection
    {
        return collect(ProductionDeliveryStatusEnum::cases())
            ->map(
                fn(ProductionDeliveryStatusEnum $value) => [
                    'value' => $value->value,
                    'label' => __('productions.delivery_status_enums.' . $value->value),
                    'color' => self::getStatusColor($value->value),
                ]
            );
    }

    public static function productionTermLabel(int $value = null): string
    {
        if (!$value) {
            return '';
        }

        if ($value % 10 === 1 && $value !== 11) {
            return $value . ' день';
        }

        if (in_array($value % 10, [2, 3, 4]) && !in_array($value, [11, 12, 13, 14])) {
            return $value . ' дня';
        }

        return $value . ' дней';
    }
}
