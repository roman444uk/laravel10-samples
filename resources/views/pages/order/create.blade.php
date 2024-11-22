@php
    use App\Models\Order;use Illuminate\Support\Facades\View;

    /**
     * @var Illuminate\Support\Collection $patientsCollection
     */

    $orderEditionDisabled = false;
    $stageEditionDisabled = false;

    View::share('crumbs', [
        [
            'label' => __('orders.orders'),
            'url' => route('order.index'),
        ],
        [
            'label' => __('orders.order_creating'),
        ],
    ]);
@endphp

@extends('layouts.main')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="form-heading">
                <h4>{{ __('orders.order_creating') }}</h4>
            </div>
            @include('pages.order.form.index', [
                'order' => new Order(),
                'stage' => null,
            ])
        </div>
    </div>

    @include('pages.order.form.modals._modal-patient-form')
    @include('pages.order.form.modals._modal-clinic-form')
@endsection

@push('scripts-app')
    {{--    <script src="{{ urlModTime('/assets/js/pages/orders/order.js') }}"></script>--}}

    @vite('resources/js/pages/order/create.js')
@endpush
