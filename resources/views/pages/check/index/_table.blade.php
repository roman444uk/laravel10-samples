@php
    use App\Classes\Helpers\Db\CheckHelper;use App\Support\Utilities\DateTime;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\App\Models\Check[] $checksCollection
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
                               name="id"
        >
            {{ __('common.number') }}
        </x-table-header-column>
        <x-table-header-column :sortable="true"
                               name="patient"
        >
            {{ __('common.date') }}
        </x-table-header-column>
        <x-table-header-column :sortable="true"
                               name="patient"
        >
            {{ __('checks.steps_count') }}
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
    @foreach($checksCollection as $check)
        <tr role="row" data-id="{{ $check->id }}">
            <td class="sorting_1">
                <div class="form-check check-tables">
                    <input class="form-check-input" type="checkbox" value="something">
                </div>
            </td>
            <td>
                {{ CheckHelper::number($check) }}
            </td>
            <td>
                {{ DateTime::renderDate($check->date) }}
            </td>
            <td>
                {{ $check->steps_count }}
            </td>
            <td>
                <x-check-status-badge>{{ $check->status }}</x-check-status-badge>
            </td>
            <td class="text-end">
                @can('sendToVerification', $check)
                    <x-action-icon class="check-send-to-verification-button text-info"
                                   fa-icon="check"
                                   :loadable="true"
                                   :data-id="$check->id"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   :title="__('checks.send_to_verification')"
                    ></x-action-icon>
                @endcan

                @can('recallFromDoctorVerification', $check)
                    <x-action-icon class="recall-from-doctor-button text-danger"
                                   fa-icon="undo"
                                   :loadable="true"
                                   :data-id="$check->id"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   :title="__('checks.recall_check_from_doctor')"
                    ></x-action-icon>
                @endcan

                @can('acceptOrReject', $check)
                    <x-action-icon class="check-accept-button text-success"
                                   fa-icon="check"
                                   :loadable="true"
                                   :data-id="$check->id"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   :title="__('common.apply')"
                    ></x-action-icon>

                    <x-action-icon class="check-reject-button text-danger"
                                   fa-icon="times"
                                   :data-id="$check->id"
                                   data-bs-toggle="modal"
                                   data-bs-target="#check-reject-form-modal"
                                   :title="__('common.reject')"
                    ></x-action-icon>
                @endcan

                <x-action-icon href="{{ $check->viewer_url }}"
                               fa-icon="search"
                               :title="__('checks.3d_viewer')"
                               target="_blank"
                               :disabled="!$check->viewer_url"
                ></x-action-icon>

                <x-action-icon class="check-update-button"
                               :fa-icon="getUser()->can('update', $check) ? 'pen-to-square' : 'eye'"
                               :loadable="true"
                               :data-id="$check->id"
                               data-bs-toggle="modal"
                               data-bs-target="#check-form-modal"
                               :title="can('update', $check) ? __('common.edit') : __('common.more_details')"
                ></x-action-icon>

                @can('deleteAny', $check)
                    <x-action-icon class="check-delete-button"
                                   fa-icon="trash-alt"
                                   :loadable="true"
                                   data-action="delete"
                                   data-url="{{ route('check.destroy', ['check' => $check]) }}"
                                   :title="__('common.delete')"
                                   :disabled="!can('delete', $check)"
                    ></x-action-icon>
                @endcan
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
