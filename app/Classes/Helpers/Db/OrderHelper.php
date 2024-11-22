<?php

namespace App\Classes\Helpers\Db;

use App\Enums\Orders\StageStatusEnum;
use App\Models\Order;
use App\Models\Stage;

class OrderHelper
{
    public static function number(Order|int $order): string
    {
        return '№' . ($order instanceof Order ? $order->id : $order);
    }

    public static function isAvailableForEdition(Order $order): bool
    {
        $isAvailableForEdition = true;

        $order->stages->each(function (Stage $stage) use (&$isAvailableForEdition) {
            $isAvailableForEdition = $stage->checkAccepted ? false : $isAvailableForEdition;
        });

        return $isAvailableForEdition;
    }

    public static function getLink(Order|int $order): string
    {
        $orderId = $order instanceof Order ? $order->id : $order;

        return '<a href="' . route('order.edit', [
                'order' => $orderId,
            ]) . '" target="_blank">' . OrderHelper::number($orderId) . '</a>';
    }

    public static function isDraft(Order $order): bool
    {
        return $order->stageActual->status === StageStatusEnum::DRAFT->value;
    }

    public static function canChatBeCreated(Order $order): bool
    {
        return !self::isDraft($order);
    }
}
