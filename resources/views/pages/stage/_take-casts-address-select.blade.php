@php
    /**
    * @var Illuminate\Database\Eloquent\Collection|App\Models\Address[] $addressesCollection
    * @var App\Models\Stage $stage
    * @var bool $stageEditionDisabled
    */
@endphp

<x-input-group-da-data-address id="take-casts"
                               name="take_casts_address_id"
                               :label="__('stages.take_casts_address')"
                               :options="$addressesCollection"
                               :selected-value="$stage->take_casts_address_id"
                               :disabled="$disabled"
                               form-modal-id="address-form-modal"
></x-input-group-da-data-address>
