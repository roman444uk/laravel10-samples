@php
    use App\Classes\Helpers\Db\OrderHelper;
    use App\Classes\Helpers\Db\StageHelper;
    use App\Data\ChatData;
    use App\Data\OrderData;
    use App\Data\StageData;
    use App\Data\UserData;
    use App\Enums\Orders\StageTabEnum;
    use App\Enums\StageSubTabEnum;
    use App\Models\Clinic;
    use App\Models\Order;
    use App\Models\Patient;
    use App\Models\Stage;
    use App\Support\Str;
    use Illuminate\Database\Eloquent\Casts\Json;
    use Illuminate\Database\Eloquent\Collection as EloquentCollection;
    use Illuminate\Support\Facades\View;

    /**
     * @var Order $order
     * @var Stage $stage
     * @var EloquentCollection|array $isReadyForWork
     * @var string $tab
     * @var string $subTab
     * @var EloquentCollection|Clinic[] $clinicsCollection
     * @var EloquentCollection|Patient[] $patientsCollection
     * @var int $chatOffCanvasWidth
     */

    $orderEditionDisabled = !OrderHelper::isAvailableForEdition($order);
    $stageEditionDisabled = !StageHelper::isAvailableForEdition($stage) || !can('update', $stage);

    View::share('crumbs', [
        [
            'label' => __('orders.orders'),
            'url' => route('order.index'),
        ],
        [
            'label' => __('orders.order_editing_with_number', ['number' => $order->id]),
        ],
    ]);
@endphp

@push('scripts-data')
    window.custom = {!! \Illuminate\Support\Js::from([
        'order' => OrderData::fromModel($order),
        'stage' => StageData::fromModel($stage),
        'tab' => $tab,
    ]) !!};
@endpush

@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-4">
            <a href="{{ route('order.index') }}" class="btn btn-primary w-100 mb-4">
                <i class="fa fa-backward me-2" aria-hidden="true"></i>{{ __('orders.to_orders_list') }}
            </a>

            <div class="widget settings-menu setting-list-menu">
                <ul>
                    @foreach(StageTabEnum::sorted() as $enum)
                        @php
                            $params = ['order' => $order, 'stage' => $stage, 'tab' => $enum->value];
                            if ($enum->value === StageTabEnum::SCANS_AND_IMPRESSIONS->value && StageHelper::isTakeCastsFilled($stage)) {
                                $params['subTab'] = StageSubTabEnum::TAKE_CASTS->value;
                            }
                        @endphp
                        <li class="nav-item" data-tab="{{ $enum->value }}">
                            <a href="{{ route('order.edit', $params) }}"
                                @class([
                                    'nav-link',
                                    'active' => $tab === $enum->value,
                                ])
                            >
                                <i class="fa fa-circle me-2" aria-hidden="true"></i>

                                <span>{{ __('orders.treatment_tabs.' . Str::delimiterFromDashToUnderscore($enum->value)) }}</span>

                                <i @class(['fa fa-exclamation-circle text-danger ready-icon', 'd-none' => $isReadyForWork->get('tabs')[$enum->value]])
                                   aria-hidden="true"
                                ></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <a href="{{ route('order.index') }}" class="btn btn-primary w-100 mb-4">
                {{ __('stages.treatment_finished') }}
            </a>
        </div>

        <div class="col-xl-9 col-md-8">
            {{--            <div class="card mb-4">--}}
            {{--                <div class="card-body">--}}
            {{--                    <div class="cd-horizontal-timeline loaded m-0">--}}
            {{--                        <div class="timeline w-100 p-0" style="max-width: none !important; height: 50px;">--}}
            {{--                            <div class="events-wrapper">--}}
            {{--                                <div class="events w-100">--}}
            {{--                                    <ol>--}}
            {{--                                        @php--}}
            {{--                                            $statuses = \App\Enums\Db\StageStatusEnum::casesChronological();--}}
            {{--                                            $step = 100 / count($statuses);--}}

            {{--                                            $order->status = \App\Enums\Db\StageStatusEnum::VERIFICATION->value;--}}

            {{--                                            $status = collect($statuses)->first(fn($status) => $status->value === $order->status);--}}
            {{--                                            $selectedIndex = array_search($status, $statuses);--}}
            {{--                                        @endphp--}}
            {{--                                        @foreach($statuses as $index => $status)--}}
            {{--                                            <li>--}}
            {{--                                                <a @class(['selected' => $status->value === $order->status, 'success' => $index < $selectedIndex])--}}
            {{--                                                   href="#{{ $index }}"--}}
            {{--                                                   style="left: {{ $index * $step }}%; width: {{ $step }}%;"--}}
            {{--                                                >--}}
            {{--                                                    <span class="relative">--}}
            {{--                                                        {{ __('orders.status_enums.' . Str::delimiterFromDashToUnderscore($status->value)) }}--}}
            {{--                                                    </span>--}}
            {{--                                                </a>--}}
            {{--                                            </li>--}}
            {{--                                        @endforeach--}}
            {{--                                    </ol>--}}
            {{--                                    <span class="filling-line" aria-hidden="true" style="transform: scaleX({{ (($index * $step) - ($step / 2)) / 1000 }});"></span>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <div class="card">
                <div class="card-body">
                    <div class="form-heading">
                        <h4>{{ __('orders.order_number', ['number' => $order->id]) }}</h4>
                    </div>
                    @include('pages.order.form.index', [
                        'order' => $order,
                        'subTab' => $subTab,
                    ])
                </div>
            </div>

            @can('sendToWork', $stage)
                <x-fixed-footer id="stage-send-to-work-wrapper"
                        @class(['fixed-button-footer width-parent text-center'])
                >
                    <x-button type="button"
                              class="btn btn-success submit-form me-2"
                              id="stage-send-to-work-button"
                              :loadable="true"
                    >
                        {{ __ ('orders.send_order_to_work') }}
                    </x-button>
                </x-fixed-footer>
            @endcan

            @can('refuseTreatment', $stage)
                <x-fixed-footer id="stage-refuse-treatment-wrapper"
                    @class(['fixed-button-footer width-parent text-center'])
                >
                    <x-button type="button"
                              class="btn btn-danger submit-form me-2"
                              id="stage-refuse-treatment-button"
                              :loadable="true"
                    >
                        {{ __ ('stages.refuse_treatment') }}
                    </x-button>
                </x-fixed-footer>
            @endcan

            @can('initiateTreatment', $stage)
                <x-fixed-footer id="stage-initiate-treatment-wrapper"
                    @class(['fixed-button-footer width-parent text-center'])
                >
                    <x-button type="button"
                              class="btn btn-success submit-form me-2"
                              id="stage-initiate-treatment-button"
                              :loadable="true"
                    >
                        {{ __ ('stages.treatment_started') }}
                    </x-button>
                </x-fixed-footer>
            @endcan

            @can('finishTreatment', $stage)
                <x-fixed-footer id="stage-finish-treatment-wrapper"
                    @class(['fixed-button-footer width-parent text-center'])
                >
                    <x-button type="button"
                              class="btn btn-success submit-form me-2"
                              id="stage-finish-treatment-button"
                              :loadable="true"
                    >
                        {{ __ ('stages.treatment_finished') }}
                    </x-button>
                </x-fixed-footer>
            @endcan
        </div>
    </div>

    <x-offcanvas id="offcanvas-chat"
                 class="offcanvas-end offcanvas-chat"
                 :header="__('chats.chat_with_laboratory')"
                 @style(['width: ' . ($chatOffCanvasWidth ? $chatOffCanvasWidth . 'px' : '50%') . ';', 'max-width: 75%', 'min-width: 25%'])
                 :resizable="true"

    >
        <chat :user="{{ Json::encode(UserData::fromModel(getUser()->load('profile.fileAvatar'))) }}"
              :order="{{ Json::encode(OrderData::fromModel($order)) }}"
              :chat="{{ Json::encode($chatWithLaboratory ? ChatData::fromModel($chatWithLaboratory) : []) }}"
              :mode-off-canvas="true"
        ></chat>
    </x-offcanvas>

    @include('pages.order.form.modals._modal-address-form')
    @include('pages.order.form.modals._modal-check-form')
    @include('pages.order.form.modals._modal-check-reject-form')
    @include('pages.order.form.modals._modal-clinic-form')
    @include('pages.order.form.modals._modal-have-problem')
    @include('pages.order.form.modals._modal-patient-form')
    @if($stage->checkAccepted)
        @include('pages.order.form.modals._modal-production-form')
    @endif
    @include('pages.order.form.modals._swal-stage-statuses')
@endsection

@pushonce('scripts-app')
    {{--    @scriptTag('/assets/js/pages/orders/order.js')--}}
    {{--    @scriptTag('/assets/js/pages/orders/order-stage.js')--}}
    {{--    @scriptTag('/assets/js/pages/orders/order-stage-fields.js')--}}
    {{--    @scriptTag('/assets/js/pages/orders/order-tab-check.js')--}}
    {{--    @scriptTag('/assets/js/pages/orders/order-tab-production.js')--}}
    {{--    @scriptTag('/assets/js/pages/orders/order-tab-take-casts.js')--}}

    @vite('resources/js/pages/order/edit.js')
@endpushonce
