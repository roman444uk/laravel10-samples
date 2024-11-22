@php
    /**
     * @var Illuminate\Database\Eloquent\Collection|App\Models\Address[] $addressesCollection
     * @var App\Models\Stage $stage
     * @var bool $stageEditionDisabled
     */
@endphp

<x-input-group-da-data-address id="stage"
                               name="delivery_address_id"
                               :label="__('stages.delivery_address')"
                               :disabled="$stageEditionDisabled"
                               :options="$addressesCollection"
                               :selected-value="$stage->delivery_address_id"
                               form-modal-id="address-form-modal"
></x-input-group-da-data-address>
