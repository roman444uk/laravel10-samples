<?php

namespace App\Classes\Helpers\Db;

use App\Enums\Orders\StageStatusEnum;
use App\Enums\Users\UserRoleEnum;
use App\Support\Str;
use Illuminate\Support\Collection;

class StageStatusHelper
{
    public static function getStatusColor(string $status): string
    {
        return match ($status) {
            StageStatusEnum::DRAFT->value => 'grey',
            StageStatusEnum::PREPARATION->value,
            StageStatusEnum::MODELING->value,
            StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value,
            StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value,
            StageStatusEnum::CHECK_VERIFICATION_BY_DOCTOR->value,
            StageStatusEnum::VERIFICATION->value => 'blue',
            default => 'green',
        };
    }

    public static function getStatusLabel(StageStatusEnum|string $status, string $role = null): string
    {
        $status = $status instanceof StageStatusEnum ? $status->value : $status;

        return __('stages.status_enums.' . ($role === UserRoleEnum::DOCTOR->value ? 'doctor.' : '') . Str::delimiterFromDashToUnderscore($status));
    }

    public static function getStatusDescriptionLabel(StageStatusEnum|string $status, string $role = null): string
    {
        $status = $status instanceof StageStatusEnum ? $status->value : $status;

        return __('stages.status_descriptions.' . ($role === UserRoleEnum::DOCTOR->value ? 'doctor.' : '') . Str::delimiterFromDashToUnderscore($status));
    }

    public static function getStatusCasesChronological(string $role = null): array
    {
        return StageStatusEnum::casesChronological($role);
    }

    public static function getStatusLinkChronological(string $status, string $role = null): string
    {
        $cases = StageStatusEnum::getValues('casesChronological', [$role]);

        return in_array($status, $cases) ? $status : self::getStatusesRoleMap($role)[$status];
    }

    public static function getStatusIndexChronological(string $status, string $role = null): ?int
    {
        $cases = StageStatusEnum::getValues('casesChronological', [$role]);

        $status = self::getStatusesRoleMap($role)[$status] ?? $status;

        return in_array($status, $cases) ? array_search($status, $cases) : null;
    }

    public static function getStatusesRoleMap(string $role = null): array
    {
        return match ($role) {
            UserRoleEnum::DOCTOR->value => [
                StageStatusEnum::MODELING->value  => StageStatusEnum::PREPARATION->value,
                StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST->value  => StageStatusEnum::PREPARATION->value,
                StageStatusEnum::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR->value  => StageStatusEnum::PREPARATION->value,
                StageStatusEnum::CHECK_VERIFICATION_BY_DOCTOR->value  => StageStatusEnum::PREPARATION->value,

                StageStatusEnum::PAYMENT_BILL_AFTER_REJECTION->value  => StageStatusEnum::PAYMENT_BILL->value,
                StageStatusEnum::PAYMENT_AWAITING->value => StageStatusEnum::PAYMENT_BILL->value,
                StageStatusEnum::PAYMENT_AWAITING_AFTER_REJECTION->value => StageStatusEnum::PAYMENT_BILL->value,

                StageStatusEnum::PRODUCTION_PREPARATION->value  => StageStatusEnum::PRODUCTION_OPTIONS->value,
                StageStatusEnum::PRODUCTION_RELEASE->value  => StageStatusEnum::PRODUCTION_OPTIONS->value,
                StageStatusEnum::PRODUCTION_PACKAGING->value  => StageStatusEnum::PRODUCTION_OPTIONS->value,
                StageStatusEnum::PRODUCTION_CONTROL->value  => StageStatusEnum::PRODUCTION_OPTIONS->value,

                StageStatusEnum::DELIVERY->value => StageStatusEnum::DELIVERY_PREPARATION->value,
                StageStatusEnum::DELIVERED->value => StageStatusEnum::DELIVERY_PREPARATION->value,
            ],
            default => [],
        };
    }

    /**
     * Get list of statuses prepared for rendering via badge dropdown component.
     */
    public static function statusDropdownBadgeOptions(Collection|array $statuses = null): Collection
    {
        return collect($statuses ? StageStatusEnum::toCases(is_array($statuses) ? $statuses : $statuses->toArray()) : StageStatusEnum::casesChronological())
            ->map(
                fn(StageStatusEnum $enum) => [
                    'value' => $enum->value,
                    'label' => __('stages.status_enums.' . Str::delimiterFromDashToUnderscore($enum->value)),
                    'color' => self::getStatusColor($enum->value),
                ]
            );
    }
}
