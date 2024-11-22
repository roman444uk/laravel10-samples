@php
    use App\Classes\Helpers\Db\StageHelper;
    use App\Classes\Helpers\Db\StageStatusHelper;
    use App\Classes\Helpers\Db\UserHelper;
    use App\Enums\Orders\OrderProductTypeEnum;
    use App\Models\Clinic;
    use App\Models\Order;
    use App\Models\Patient;
    use App\Models\Stage;
    use App\Support\Str;
    use App\Support\Utilities\DateTime;
    use Illuminate\Support\Collection;

    /**
     * @var bool $orderEditionDisabled
     * @var bool $stageEditionDisabled
     * @var Order $order
     * @var Stage $stage
     * @var string $tab
     * @var Collection|Clinic[] $clinicsCollection
     * @var Collection|Patient[] $patientsCollection
     */

    $jsData = [
        'order' => $order?->toArray()
    ];
@endphp

<form action="{{ $order?->id ? route('order.update', ['order' => $order]) : route('order.store') }}"
      method="{{ $order?->id ? 'PUT' : 'POST' }}"
      id="order-form"
>
    @csrf

    <div class="row">
        <div @class([$stage ? 'col-md-6 col-lg-8 col-xl-8' : 'col-md-12 col-lg-12 col-xl-12'])>
            @php
                $productTypeOptions = OrderProductTypeEnum::getTranslationMap('orders.product_types');
                if ($stage) {
                    $productTypeOptions->prepend('', '');
                }
            @endphp
            <div class="form-group local-forms">
                <x-form.select id="product-type"
                               class="select"
                               name="product_type"
                               :options="$productTypeOptions"
                               :selected-value="old('product_type', $order?->product_type)"
                               :label="__('orders.product_type')"
                               :required="true"
                               :disabled="$stageEditionDisabled"
                ></x-form.select>
            </div>

            <div @class(['input-group', 'disabled' => $stageEditionDisabled])>
                <div class="form-group local-forms">
                    <x-form.select id="patient-id"
                                   class="select"
                                   name="patient_id"
                                   :options="$patientsCollection"
                                   :selected-value="old('patient_id', $order?->patient_id)"
                                   :label="__('orders.patient')"
                                   :required="true"
                                   :disabled="$stageEditionDisabled"
                    ></x-form.select>
                </div>
                <span id="edit-patient-button" @class([
                    'input-group-text',
                ])>
                    <i class="fa fa-edit"></i>
                </span>
                <span id="add-patient-button" @class([
                    'input-group-text',
                ]) @if(!$stageEditionDisabled) data-bs-toggle="modal" data-bs-target="#patient-form-modal" @endif>
                    <i class="fa fa-add"></i>
                </span>
            </div>

            <div @class(['input-group', 'disabled' => $stageEditionDisabled])>
                <div class="form-group local-forms">
                    <x-form.select id="clinic-id"
                                   class="select"
                                   name="clinic_id"
                                   :options="$clinicsCollection"
                                   :selected-value="old('clinic_id', $order?->clinic_id)"
                                   :label="__('orders.clinic')"
                                   :required="true"
                                   :disabled="$stageEditionDisabled"
                    ></x-form.select>
                </div>
                <span id="add-clinic-button" @class([
                    'input-group-text',
                ]) @if(!$stageEditionDisabled) data-bs-toggle="modal" data-bs-target="#clinic-form-modal" @endif>
                    <i class="fa fa-add"></i>
                </span>
            </div>

            @can('changeEmployeesAny', Stage::class)
                @php
                    $employeesDisabled = !can('changeEmployees', [$stage]);
                @endphp
                <div class="stage-row">
                    <div class="form-group local-forms">
                        <x-form.select id="clinical-specialist-id"
                                       class="select filter-field"
                                       name="clinical_specialist_id"
                                       :options="UserHelper::getSelectOptions($clinicalSpecialistsCollection)"
                                       :selected-value="old('clinical_specialist_id', $stage->clinical_specialist_id)"
                                       :label="__('users.role_enums.clinical_specialist')"
                                       :disabled="$employeesDisabled"
                        ></x-form.select>
                    </div>

                    <div class="form-group local-forms">
                        <x-form.select id="modeler-3d-id"
                                       class="select filter-field"
                                       name="modeler_3d_id"
                                       :options="UserHelper::getSelectOptions($modelers3dCollection)"
                                       :selected-value="old('modeler_3d_id', $stage->modeler_3d_id)"
                                       :label="__('users.role_enums.modeler_3d')"
                                       :disabled="$employeesDisabled"
                        ></x-form.select>
                    </div>

                    <div class="form-group local-forms">
                        <x-form.select id="technician-digitizer-id"
                                       class="select filter-field"
                                       name="technician_digitizer_id"
                                       :options="UserHelper::getSelectOptions($technicianDigitizersCollection)"
                                       :selected-value="old('technician_digitizer_id', $stage->technician_digitizer_id)"
                                       :label="__('users.role_enums.technician_digitizer')"
                                       :disabled="$employeesDisabled"
                        ></x-form.select>
                    </div>

                    <div class="form-group local-forms">
                        <x-form.select id="technician-production-id"
                                       class="select filter-field"
                                       name="technician_production_id"
                                       :options="UserHelper::getSelectOptions($technicianProductionsCollection)"
                                       :selected-value="old('technician_production_id', $stage->technician_production_id)"
                                       :label="__('users.role_enums.technician_production')"
                                       :disabled="$employeesDisabled"
                        ></x-form.select>
                    </div>

                    <div class="form-group local-forms">
                        <x-form.select id="logistic-manager-id"
                                       class="select filter-field"
                                       name="logistic_manager_id"
                                       :options="UserHelper::getSelectOptions($logisticManagersCollection)"
                                       :selected-value="old('logistic_manager_id', $stage->logistic_manager_id)"
                                       :label="__('users.role_enums.logistic_manager')"
                                       :disabled="$employeesDisabled"
                        ></x-form.select>
                    </div>
                </div>
            @endcan
        </div>

        @if($stage)
            <div class="col-md-6 col-lg-4 col-xl-4">
                <div class="d-flex justify-content-between mb-4">
                    <span>{{ __('orders.status') }}:</span>
                    <span>
                        <i class="fa fa-question-circle cursor-pointer stage-statuses-modal-open status-{{ StageStatusHelper::getStatusColor($stage->status) }}"
                           data-swal-toggle="modal"
                           data-swal-target="#stage-statuses-modal"
                           data-swal-template="#swal-stage-statuses"
                        ></i>
                        @can('changeStatusDirectly', $stage)
                            <x-custom-badge-dropdown :id="'stage-status-' . $stage->id"
                                                     :options="StageStatusHelper::statusDropdownBadgeOptions(StageHelper::getAvailableStatusesForUpdating($stage, getUser()))"
                                                     :selected-value="$stage->status"
                                                     :data-id="$stage->id"
                                                     :disabled="false"
                            ></x-custom-badge-dropdown>
                        @else
                            <x-stage-status-badge>{{ $stage->status }}</x-stage-status-badge>
                        @endcan
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <span>{{ __('orders.order') }}:</span>
                    <span>
                        <x-custom-badge class="status-green">
                            {{ __('orders.product_types.' . $order->product_type) }}
                        </x-custom-badge>
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <span>{{ __('orders.beginning') }}:</span>
                    <span>
                        {{ DateTime::renderDate($order->created_at) }}
                    </span>
                </div>

                @if($stage->guarantee_date)
                    <div class="d-flex justify-content-between mb-4">
                        <span>{{ __('orders.guarantee') }}:</span>
                        <span>
                        {{ DateTime::renderDate($stage->guarantee_date) }}
                    </span>
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if($order?->id)
        <div class="col-12">
            <div class="text-end">
                @if($chatWithLaboratory)
                    <x-button type="button"
                              class="btn btn-primary submit-form me-2"
                              id="chat"
                              fa-icon="comment"
                              data-bs-toggle="offcanvas"
                              data-bs-target="#offcanvas-chat"
                              aria-controls="offcanvasRight"
                              :loadable="true"
                    >
                        {{ __ ('orders.chat') }}
                    </x-button>
                @endif

                @if(isUserDoctor())
                    <x-button type="button"
                              class="btn-primary submit-form me-2"
                              id="have-problem"
                              fa-icon="comment"
                              data-bs-toggle="modal"
                              data-bs-target="#have-problem-form-modal"
                    >
                        {{ __ ('orders.i_have_a_problem') }}
                    </x-button>

                    @can('orderContinuation', $stage)
                        <x-button type="button"
                                  class="btn-primary submit-form me-2"
                                  id="order-next-production"
                                  fa-icon="comment"
                                  :loadable="true"
                        >
                            {{ __ ('orders.order_continuation') }}
                        </x-button>
                    @endcan
                @endif
            </div>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-bottom">
                @foreach($order->stages as $stageIndex => $stageModel)
                    <li class="nav-item">
                        <a @class(['nav-link', 'active' => $stageModel->id === $stage->id])
                           href="{{ route('order.edit', ['order' => $order, 'stage' => $stageModel, 'tab' => $tab]) }}"
                        >
                            {{ StageHelper::getTitle($stageModel) }}
                        </a>
                    </li>
                @endforeach
                @can('create', [Stage::class, $order])
                    <li style="padding-top: 3px;">
                        <x-button type="button"
                                  class="btn btn-primary submit-form me-2"
                                  id="add-correction"
                                  fa-icon=" comment"
                                  :loadable="true"
                        >
                            {{ __('stages.correction_needed') }}
                        </x-button>
                    </li>
                @endcan
            </ul>

            <input type="hidden" name="stages[{{ $stage->id }}][id]" value="{{ $stage->id }}">

            <div class="tab-content">
                <div class="tab-pane show active" id="stage-tab-{{ $stage->id }}" data-id="{{ $stage->id }}">
                    @include('pages.order.form.tabs.'.$tab, [
                        'order' => $order,
                        'stage' => $stage,
                        'subTab' => $subTab,
                    ])
                </div>
            </div>
        </div>
    @endif

    @if(!$order || !$order->id)
        <div class="col-12">
            <div class="doctor-submit text-end">
                <x-button type="submit"
                          class="btn btn-primary submit-form me-2"
                          id="order-save-button"
                          :loadable="true"
                >
                    {{ __('buttons.save') }}
                </x-button>
            </div>
        </div>
    @endif
</form>
