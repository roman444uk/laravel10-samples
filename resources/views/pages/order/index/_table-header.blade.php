@php
    use App\Classes\Helpers\Db\DoctorHelper;use App\Classes\Helpers\Db\UserHelper;use App\Data\Filters\OrderDataFilter;use App\Models\Doctor;use App\Models\Order;use App\Models\User;use Illuminate\Database\Eloquent\Collection;

    /**
     * @var Collection|User[] $clientManagersCollection
     * @var Collection|User[] $clinicalSpecialistsCollection
     * @var Collection|Doctor[]|null $doctorsCollection
     * @var Collection|Order[] $ordersCollection
     * @var OrderDataFilter $orderFilter
     */
@endphp

<form id="orders-filter-form" action="{{ route('order.index') }}">
    <!-- Table Header -->
    <div class="page-table-header mb-2">
        <div class="row align-items-center">
            <div class="col">
                <div class="doctor-table-blk">
                    <!--<h3>Patient List</h3>-->
                    <div class="doctor-search-blk">
                        <div class="top-nav-search table-search-blk">
                            <div style="position: relative; width: 270px; margin: 0;">
                                <input type="text"
                                       class="form-control filter-field"
                                       name="search"
                                       value="{{ old('search', $orderFilter?->search) }}"
                                       placeholder="{{ __('common.search') }}"
                                >
                                <a class="btn">
                                    <img src="/assets/img/icons/search-normal.svg" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="add-group">
                            @can('create', Order::class)
                                <a href="{{ route('order.create') }}"
                                   class="btn btn-primary add-pluss ms-2"
                                >
                                    <img src="/assets/img/icons/plus.svg" alt="">
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Table Header -->

    <!-- Table Header Filter -->
    <div class="staff-search-table">
        <div class="row">
            <div class="col-12 col-md-6 col-xl-4">
                <div class="form-group local-forms">
                    <x-form.select id="status"
                                   class="select filter-field"
                                   name="status"
                                   :options="\App\Classes\Helpers\Db\StageHelper::getSelectOptions(
                                        \App\Enums\Orders\StageStatusEnum::getTranslationMap('stages.status_enums'), ''
                                    )"
                                   :selected-value="old('status')"
                                   :label="__('common.status')"
                    ></x-form.select>
                </div>
            </div>
            @if($doctorsCollection)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="form-group local-forms">
                        <x-form.select id="doctor-id"
                                       class="select filter-field"
                                       name="doctor_id"
                                       :options="DoctorHelper::getSelectOptions($doctorsCollection, '')"
                                       :selected-value="old('client_manager_id', $orderFilter?->doctorId)"
                                       :label="__('users.role_enums.doctor')"
                        ></x-form.select>
                    </div>
                </div>
            @endif
            <div class="col-12 col-md-6 col-xl-4">
                <div class="form-group local-forms">
                    <x-form.select id="client-manager-id"
                                   class="select filter-field"
                                   name="client_manager_id"
                                   :options="UserHelper::getSelectOptions($clientManagersCollection, '')"
                                   :selected-value="old('client_manager_id', $orderFilter?->clientManagerId)"
                                   :label="__('users.role_enums.client_manager')"
                    ></x-form.select>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="form-group local-forms">
                    <x-form.select id="clinical-specialist-id"
                                   class="select filter-field"
                                   name="clinical_specialist_id"
                                   :options="UserHelper::getSelectOptions($clinicalSpecialistsCollection, '')"
                                   :selected-value="old('clinical_specialist_id', $orderFilter?->clinicalSpecialistId)"
                                   :label="__('users.role_enums.clinical_specialist')"
                    ></x-form.select>
                </div>
            </div>
        </div>
    </div>
    <!-- /Table Header Filter -->
</form>
