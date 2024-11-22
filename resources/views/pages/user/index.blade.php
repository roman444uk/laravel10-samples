@php
    use App\Classes\Helpers\Db\UserHelper;use App\Classes\Helpers\PhoneHelper;use App\Models\User;use Illuminate\Support\Facades\View;

    /**
     * @var Illuminate\Database\Eloquent\Collection|User[] $usersCollection
     */

    View::share('crumbs', [
        [
            'label' => __('users.users'),
        ]
    ]);
@endphp

@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-sm-12">

            <div class="card card-table show-entire">
                <div class="card-body">

                    <!-- Table Header -->
                    <div class="page-table-header mb-2">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="doctor-table-blk">
                                    <!--<h3>Patient List</h3>-->
                                    <div class="doctor-search-blk">
                                        <div class="top-nav-search table-search-blk">
                                            <form>
                                                <input type="text" class="form-control"
                                                       placeholder="{{ __('common.search') }}"
                                                >
                                                <a class="btn">
                                                    <img src="/assets/img/icons/search-normal.svg" alt="">
                                                </a>
                                            </form>
                                        </div>
                                        <div class="add-group">
                                            <a href="{{ route('user.create') }}"
                                               class="btn btn-primary add-pluss ms-2"
                                            >
                                                <img src="/assets/img/icons/plus.svg" alt="">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Table Header -->

                    <div class="table-responsive">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length" id="DataTables_Table_0_length"><label>Show <select
                                                name="DataTables_Table_0_length" aria-controls="DataTables_Table_0"
                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select> entries</label></div>
                                </div>
                                <div class="col-sm-12 col-md-6"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="users-table"
                                           class="table border-0 custom-table comman-table datatable mb-0 dataTable no-footer"
                                           role="grid"
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
                                                                   name="full_name"
                                            >
                                                {{ __('users.full_name') }}
                                            </x-table-header-column>
                                            <x-table-header-column :sortable="true"
                                                                   name="role"
                                            >
                                                {{ __('users.role') }}
                                            </x-table-header-column>
                                            <x-table-header-column :sortable="true"
                                                                   name="phone"
                                            >
                                                {{ __('users.phone') }}
                                            </x-table-header-column>
                                            <x-table-header-column :sortable="true"
                                                                   name="email"
                                            >
                                                {{ __('users.email') }}
                                            </x-table-header-column>
                                            <x-table-header-column :sortable="true"
                                                                   name="created_at"
                                            >
                                                {{ __('users.created_at') }}
                                            </x-table-header-column>
                                            <x-table-header-column :sortable="false"></x-table-header-column>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($usersCollection as $user)
                                            <tr role="row" data-id="{{ $user->id }}">
                                                <td class="sorting_1">
                                                    <div class="form-check check-tables">
                                                        <input class="form-check-input" type="checkbox"
                                                               value="something">
                                                    </div>
                                                </td>
                                                <td class="profile-image">
                                                    <a href="{{ route('user.edit', ['user' => $user]) }}">
                                                        <img width="28" height="28"
                                                             src="/assets/img/profiles/avatar-01.jpg"
                                                             class="rounded-circle m-r-5"
                                                             alt=""
                                                        >
                                                        {{ $user->profile->full_name }}
                                                    </a>
                                                </td>
                                                <td>{{ UserHelper::getRoleLabel($user->role) }}</td>
                                                <td>
                                                    <a href="javascript:;">
                                                        {{ $user->profile->phone ? PhoneHelper::toFormat($user->profile->phone) : '' }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ $user->email }}
                                                </td>
                                                <td>
                                                    {{ $user->created_at->format('d.m.Y') }}
                                                </td>
                                                <td class="text-end">
                                                    @can('logInAs', User::class)
                                                        <x-action-icon
                                                            :class="$user ? ' log-in-as-user text-info' : ' text-danger cursor-default'"
                                                            fa-icon="right-to-bracket icon-lg"
                                                            href="#"
                                                            :title="__('auth.log_in_as_user')"
                                                        ></x-action-icon>
                                                    @endcan

                                                    @can('updateAny', User::class)
                                                        <x-action-icon fa-icon="pen-to-square"
                                                                       :href="route('user.edit', ['user' => $user])"
                                                                       :title="__('common.edit')"
                                                                       :disabled="!can('update', $user)"
                                                        ></x-action-icon>
                                                    @endcan

                                                    @can('deleteAny', $user)
                                                        <x-action-icon fa-icon="trash-alt"
                                                                       :loadable="true"
                                                                       data-action="delete"
                                                                       :data-url="route('user.destroy', ['user' => $user])"
                                                                       :title="__('common.delete')"
                                                                       :disabled="!can('delete', $user)"
                                                        ></x-action-icon>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <x-paginate-footer :collection="$usersCollection"></x-paginate-footer>

                            {{--                            <div class="row">--}}
                            {{--                                <div class="col-sm-12 col-md-5">--}}
                            {{--                                    <div class="dataTables_info" id="DataTables_Table_0_info" role="status"--}}
                            {{--                                         aria-live="polite"--}}
                            {{--                                    >--}}
                            {{--                                        {{ Pagination::shownRecords($usersCollection) }}--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="col-sm-12 col-md-7">--}}
                            {{--                                    {{ $usersCollection->links('layouts.partials.pagination') }}--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-app')
    @vite('resources/js/pages/user/index.js')
@endpush
