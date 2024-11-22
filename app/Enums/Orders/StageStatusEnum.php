<?php

namespace App\Enums\Orders;

use App\Enums\Users\UserRoleEnum;
use App\Traits\EnumTrait;

enum StageStatusEnum: string
{
    use EnumTrait;

    case CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR = 'verification-by-clinical-director';
    case CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST = 'verification-by-clinical-specialist';
    case CHECK_VERIFICATION_BY_DOCTOR = 'verification-by-doctor';
    case COMPLETED = 'completed';
    case DELIVERED = 'delivered';
    case DELIVERY = 'delivery';
    case DELIVERY_PREPARATION = 'delivery-preparation';
    case DRAFT = 'draft';
    case MODELING = 'modeling';
    case TREATMENT = 'treatment';
    case PAYMENT_AWAITING = 'payment-awaiting';
    case PAYMENT_AWAITING_AFTER_REJECTION = 'payment-awaiting-after-rejection';
    case PAYMENT_BILL = 'payment-bill';
    case PAYMENT_BILL_AFTER_REJECTION = 'payment-bill-after-rejection';
    case PREPARATION = 'preparation';
    case PRODUCTION_CONTROL = 'production-control';
    case PRODUCTION_OPTIONS = 'production-options';
    case PRODUCTION_PACKAGING = 'production-packaging';
    case PRODUCTION_PREPARATION = 'production-preparation';
    case PRODUCTION_RELEASE = 'production-release';
    case VERIFICATION = 'verification';

    public static function casesChronological(string $role = null): array
    {
        return match ($role) {
            UserRoleEnum::DOCTOR->value => [
                self::DRAFT,
                self::VERIFICATION,
                self::PREPARATION,
                self::CHECK_VERIFICATION_BY_DOCTOR,
                self::PAYMENT_BILL,
                self::PRODUCTION_OPTIONS,
                self::DELIVERY_PREPARATION,
                self::TREATMENT,
                self::COMPLETED,
            ],
            default => [
                self::DRAFT,
                self::VERIFICATION,
                self::PREPARATION,
                self::MODELING,
                self::CHECK_VERIFICATION_BY_CLINICAL_SPECIALIST,
                self::CHECK_VERIFICATION_BY_CLINICAL_DIRECTOR,
                self::CHECK_VERIFICATION_BY_DOCTOR,
                self::PAYMENT_BILL,
                self::PAYMENT_BILL_AFTER_REJECTION,
                self::PAYMENT_AWAITING,
                self::PAYMENT_AWAITING_AFTER_REJECTION,
                self::PRODUCTION_OPTIONS,
                self::PRODUCTION_PREPARATION,
                self::PRODUCTION_RELEASE,
                self::PRODUCTION_PACKAGING,
                self::PRODUCTION_CONTROL,
                self::DELIVERY_PREPARATION,
                self::DELIVERY,
                self::DELIVERED,
                self::TREATMENT,
                self::COMPLETED,
            ],
        };
    }
}
