@php
    /**
     * @var bool $stageEditionDisabled
     * @var App\Models\Stage $stage
     */
@endphp

<div class="form-group local-forms stage-row">
    @include('pages.stage._delivery-address-select', [
        'addressesCollection' => $addressesCollection,
        'stage' => $stage,
    ])
</div>

@include('pages.production.index._table-delivery-addresses', [
    'productionsCollection' => $stage->checkAccepted?->productions,
])

