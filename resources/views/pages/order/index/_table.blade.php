@php
    use App\Classes\Helpers\Db\DoctorHelper;
    use App\Classes\Helpers\Db\UserHelper;
    use App\Models\Order;
    use App\Support\Utilities\DateTime;
    use App\Support\Utilities\Pagination;

    /**
     * @var Illuminate\Database\Eloquent\Collection|Order[] $ordersCollection
     */
@endphp

<div class="table-responsive">
    <div id="orders-table-wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="dataTables_length" id="DataTables_Table_0_length">
                    <label>
                        Show
                        <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0"
                                class="custom-select custom-select-sm form-control form-control-sm"
                        >
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        entries
                    </label>
                </div>
            </div>
            <div class="col-sm-12 col-md-6"></div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table id="orders-table"
                       class="table border-0 custom-table comman-table datatable mb-0 dataTable no-footer"
                       role="grid" aria-describedby="DataTables_Table_0_info"
                >
                    <thead>
                    <tr role="row">
                        <x-table-header-column class="sorting_asc"
                                               :sortable="false"
                                               rowspan="1"
                                               colspan="1"
                        >
                            <div class="form-check check-tables">
                                <input class="form-check-input" type="checkbox" value="something">
                            </div>
                        </x-table-header-column>
                        <x-table-header-column :sortable="true"
                                               name="id"
                        >
                            {{ __('ID') }}
                        </x-table-header-column>
                        <x-table-header-column :sortable="true"
                                               name="patient"
                        >
                            {{ __('orders.patient') }}
                        </x-table-header-column>
                        <x-table-header-column :sortable="true"
                                               name="patient"
                        >
                            {{ __('orders.product_type') }}
                        </x-table-header-column>
                        <x-table-header-column :sortable="true"
                                               name="patient"
                        >
                            {{ __('orders.created_at') }}
                        </x-table-header-column>
                        <x-table-header-column :sortable="true"
                                               name="status"
                        >
                            {{ __('common.status') }}
                        </x-table-header-column>
                        @if(!isUserDoctor())
                            <x-table-header-column :sortable="true"
                                                   name="doctor"
                            >
                                {{ __('users.role_enums.doctor') }}
                            </x-table-header-column>
                        @endif
                        <x-table-header-column :sortable="true"
                                               name="client_manager"
                        >
                            {{ __('users.role_enums.client_manager') }}
                        </x-table-header-column>
                        <x-table-header-column :sortable="true"
                                               name="clinical_specialist"
                        >
                            {{ __('users.role_enums.clinical_specialist') }}
                        </x-table-header-column>
                        <x-table-header-column :sortable="false"></x-table-header-column>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ordersCollection as $index => $order)
                        <tr role="row" data-id="{{ $order->id }}">
                            <td class="sorting_1">
                                <div class="form-check check-tables">
                                    <input class="form-check-input" type="checkbox"
                                           value="something">
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('order.edit', ['order' => $order]) }}">
                                    {{ $order->id }}
                                </a>
                            </td>
                            <td class="profile-image">
{{--                                <a href="profile.html">--}}
                                    {{ $order->patient?->full_name }}
{{--                                </a>--}}
                            </td>
                            <td>
                                {{ __('orders.product_types.' . $order->product_type) }}
                            </td>
                            <td>
                                {{ DateTime::renderDate($order->created_at) }}
                            </td>
                            <td>
                                <x-stage-status-badge>{{ $order->stageActual->status }}</x-stage-status-badge>
                            </td>
                            @if(!isUserDoctor())
                                <td>
                                    {{ UserHelper::getFullName($order->doctor->user) }}
                                </td>
                            @endif
                            <td>
                                @if($order->doctor->clientManager)
                                    {{ UserHelper::getFullName($order->doctor->clientManager) }}
                                @endif
                            </td>
                            <td>
                                @if($order->stageActual->clinicalSpecialist)
                                    {{ UserHelper::getFullName($order->stageActual->clinicalSpecialist) }}
                                @endif
                            </td>
                            <td class="text-end">
                                @can('viewAny', Order::class)
                                    <x-action-icon fa-icon="eye"
                                                   :href="route('order.edit', ['order' => $order])"
                                                   :title="__('common.view')"
                                    ></x-action-icon>
                                @endcan

                                @can('deleteAny', Order::class)
                                    <x-action-icon fa-icon="trash-alt"
                                                   :loadable="true"
                                                   data-action="delete"
                                                   data-url="{{ route('order.destroy', ['order' => $order]) }}"
                                                   :title="__('common.delete')"
                                                   :disabled="!can('delete', $order)"
                                    ></x-action-icon>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <x-paginate-footer :collection="$ordersCollection"></x-paginate-footer>
    </div>
</div>
