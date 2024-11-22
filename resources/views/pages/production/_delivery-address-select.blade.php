@php
    /**
     * @var Illuminate\Database\Eloquent\Collection|App\Models\Address[] $addressesCollection
     * @var App\Models\Production $production
     */
@endphp

<x-input-group-da-data-address :id="'production-' . $production->id"
                               name="delivery_address_id"
                               :label="__('stages.delivery_address')"
                               :options="$addressesCollection"
                               :selected-value="$production->delivery_address_id"
                               form-modal-id="address-form-modal"
                               :entity-id="$production->id"
                               :disabled="$production->delivery_address_confirmed || $production->delivery_status !== \App\Enums\Orders\ProductionDeliveryStatusEnum::NEW->value"
></x-input-group-da-data-address>
