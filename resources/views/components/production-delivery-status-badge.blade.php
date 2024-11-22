@php
    use App\Classes\Helpers\Db\ProductionHelper;

    /**
     * @var string $slot
     */

    $color = ProductionHelper::getStatusColor(trim(strval($slot)));
@endphp

@if($slot && $color)
    <x-custom-badge @class(['status-' . $color])>
        {{ __('productions.delivery_status_enums.' . $slot) }}
    </x-custom-badge>
@endif
