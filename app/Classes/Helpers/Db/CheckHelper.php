<?php

namespace App\Classes\Helpers\Db;

use App\Data\CheckData;
use App\Enums\Orders\StageTabEnum;
use App\Models\Check;
use App\Models\Production;

class CheckHelper
{
    public static function number(Check|CheckData $check): string
    {
        return '№' . $check->number;
    }

    public static function getAvailableStepsCountForProduction(
        Check $check, bool $forDoctor = false, $exceptLatestProduction = false
    ): int
    {
        $availableStepsCount = self::getAvailableStepsCountTotal($check, $exceptLatestProduction);

        return $forDoctor ? min(15, $availableStepsCount) : $availableStepsCount;
    }

    public static function getAvailableStepsCountTotal(Check $check, bool $exceptLatestProduction = false): int
    {
        return $check->steps_count - self::getPassedSteps($check, $exceptLatestProduction);
    }

    public static function getNextStepForProduction(Check $check): int
    {
        return self::getPassedSteps($check) + 1;
    }

    public static function getPassedSteps(Check $check, bool $exceptLatestProduction = false): int
    {
        $count = 0;

        $check->productions->each(function (Production $production) use (&$count, $check, $exceptLatestProduction) {
            if ($exceptLatestProduction && $production->id === $check->latestProduction->id) {
                return;
            }

            $count += ProductionHelper::getStepsCount($production);
        });

        return $count;
    }

    public static function canNewProductionBeCreated(Check $check): bool
    {
        return $check->productions->count() === 0 || $check->productions->last()->step_to < $check->steps_count;
    }

    public static function getViewerLink(Check $check): ?string
    {
        if (!$check->viewer_url) {
            return null;
        }

        return '<a href="' . $check->viewer_url . '" target="_blank">' . $check->viewer_url . '</a>';
    }

    public static function getLink(Check|CheckData $check): string
    {
        $orderId = $check instanceof Check ? $check->stage->order->id : $check->orderId;

        return '<a href="' . route('order.edit', [
                'order' => $orderId,
                'stage' => $check->stageId,
                'tab' => StageTabEnum::CHECK->value,
            ]) . '" target="_blank">' . CheckHelper::number($check) . '</a>';
    }
}
