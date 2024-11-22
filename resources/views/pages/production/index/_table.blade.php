@php
    use App\Classes\Helpers\Db\ProductionHelper;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\App\Models\Production[] $productionsCollection
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
            {{ __('checks.steps') }}
        </x-table-header-column>
        <x-table-header-column :sortable="true"
                               name="production_term"
        >
            {{ __('productions.production_term') }}
        </x-table-header-column>
        <x-table-header-column :sortable="true"
                               name="patient"
        >
            {{ __('common.status') }}
        </x-table-header-column>
        <x-table-header-column :sortable="true"></x-table-header-column>
    </tr>
    </thead>
    <tbody>
    @foreach($productionsCollection as $index => $production)
        @php
            $isLastRecord = $index === $productionsCollection->count() - 1;
        @endphp

        <tr role="row" data-id="{{ $production->id }}">
            <td class="sorting_1">
                <div class="form-check check-tables">
                    <input class="form-check-input" type="checkbox" value="something">
                </div>
            </td>
            <td>
                {{ $production->phase }}
            </td>
            <td>
                @if($production->step_to)
                    {{ $production->step_from }} - {{ $production->step_to }}
                @else
                    {{ __('common.coordination') }}
                @endif
            </td>
            <td>
                {{ ProductionHelper::productionTermLabel($production->production_term) }}
            </td>
            <td>
                <x-production-status-badge>{{ $production->status }}</x-production-status-badge>
            </td>
            <td class="text-end">
                @can('sendToWork', $production)
                    <x-action-icon class="production-send-to-work-button text-info"
                                   fa-icon="check"
                                   :loadable="true"
                                   :data-id="$production->id"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   :title="__('common.send_to_work')"
                    ></x-action-icon>
                @endcan

                @can('update', $production)
                    <x-action-icon @class(['production-edit-button' => $isLastRecord])
                                   fa-icon="pen-to-square"
                                   :loadable="true"
                                   :data-id="$production->id"
                                   data-bs-toggle="modal"
                                   data-bs-target="#production-form-modal"
                                   :title="__('common.edit')"
                                   :disabled="!$isLastRecord"
                    ></x-action-icon>
                @endcan

                @can('delete', $production)
                    <x-action-icon fa-icon="trash-alt"
                                   :loadable="true"
                                   data-action="delete"
                                   data-url="{{ route('production.destroy', ['production' => $production]) }}"
                                   :title="__('common.delete')"
                                   :disabled="!$isLastRecord"
                    ></x-action-icon>
                @endcan
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
