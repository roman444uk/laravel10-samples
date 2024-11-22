@php
    use App\Enums\Orders\ProductionStatusEnum;

    /**
     * @var string $slot
     */

    $color = match (strval($slot)) {
        ProductionStatusEnum::DELIVERED->value => 'green',
        ProductionStatusEnum::NEW->value => 'grey',
        ProductionStatusEnum::PRODUCTION->value => 'blue',
        default => null,
    };
@endphp

<x-custom-badge @class(['status-' . $color])>
    {{ __('productions.status_enums.' . $slot) }}
</x-custom-badge>
