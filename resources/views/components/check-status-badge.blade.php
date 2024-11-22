@php
    use App\Enums\Orders\CheckStatusEnum;
    use App\Support\Str;

    /**
     * @var string $slot
     */

    $color = match (strval($slot)) {
        CheckStatusEnum::ACCEPTED->value => 'green',
        CheckStatusEnum::NEW->value => 'grey',
        CheckStatusEnum::REJECTED_BY_CLINICAL_SPECIALIST->value,
        CheckStatusEnum::REJECTED_BY_CLINICAL_DIRECTOR->value,
        CheckStatusEnum::REJECTED_BY_DOCTOR->value => 'red',
        CheckStatusEnum::VERIFICATION_BY_CLINICAL_SPECIALIST->value,
        CheckStatusEnum::VERIFICATION_BY_CLINICAL_DIRECTOR->value,
        CheckStatusEnum::VERIFICATION_BY_DOCTOR->value => 'blue',
        default => null,
    };
@endphp

<x-custom-badge @class(['status-' . $color])>
    {{ __('checks.status_enums.' . Str::delimiterFromDashToUnderscore($slot)) }}
</x-custom-badge>
