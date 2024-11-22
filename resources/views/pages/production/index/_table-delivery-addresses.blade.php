@php
    use App\Classes\Helpers\Db\ProductionHelper;

    /**
     * @var Illuminate\Database\Eloquent\Collection|App\Models\Production[]|null $productionsCollection
     * @var bool $editionDisabled
     */
@endphp

<table id="checks-table"
       class="table border-0 custom-table comman-table datatable mb-0 dataTable no-footer"
       role="grid"
>
    <thead>
    <tr role="row">
        <x-table-header-column :sortable="true"
                               rowspan="1"
                               colspan="1"
                               style="width: 15px;"
        >
            <div class="form-check check-tables">
                <input class="form-check-input" type="checkbox" value="something">
            </div>
        </x-table-header-column>
        <x-table-header-column :sortable="true"
                               name="patient"
        >
            {{ __('productions.phase') }}
        </x-table-header-column>
        <x-table-header-column :sortable="true"
                               name="patient"
        >
            {{ __('stages.delivery_address') }}
        </x-table-header-column>
        <x-table-header-column :sortable="true"
                               name="patient"
        >
            {{ __('productions.confirmed') }}
        </x-table-header-column>
        <x-table-header-column :sortable="true"
                               name="patient"
        >
            {{ __('productions.delivery_status') }}
        </x-table-header-column>
        <x-table-header-column :sortable="true"
                               name="patient"
        >
            {{ __('checks.steps') }}
        </x-table-header-column>
    </tr>
    </thead>
    <tbody>
    @foreach($productionsCollection ?? [] as $index => $production)
        <tr role="row" data-id="{{ $production->id }}">
            <td class="sorting_1">
                <div class="form-check check-tables">
                    <input class="form-check-input" type="checkbox" value="something">
                </div>
            </td>
            <td>
                {{ $production->phase }}
            </td>
            <td class="production-row" style="width: 40%">
                @include('pages.production._delivery-address-select', [
                    'addressesCollection' => $addressesCollection,
                    'production' => $production,
                ])
            </td>
            <td>
                @if($production->delivery_address_confirmed)
                    <x-custom-badge-yes-no :value="$production->delivery_address_confirmed"></x-custom-badge-yes-no>
                @elseif($production->delivery_address_id)
                    <x-button type="button"
                              class="btn production-confirm-button"
                              data-id="{{ $production->id }}"
                    >{{ __('buttons.confirm') }}</x-button>
                @endif
            </td>
            <td>
                <x-custom-badge-dropdown :id="'production-delivery-status-' . $production->id"
                                         :options="ProductionHelper::deliveryStatusDropdownBadgeOptions()"
                                         :selected-value="$production->delivery_status"
                                         :data-id="$production->id"
                                         :disabled="!$production->delivery_address_confirmed"
                ></x-custom-badge-dropdown>
            </td>
            <td>
                {{ $production->step_from }} - {{ $production->step_to }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
