@php
    use App\Classes\Helpers\Db\OrderHelper;

    /**
     * @var App\Models\Stage $stage
     */
@endphp

{{ __('stages.request_received_for_taking_casts_for_order', [
    'number' => OrderHelper::number($stage->order),
]) }}
