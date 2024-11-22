@php
    use App\Models\Doctor;
    use App\Models\Order;
    use App\Models\User;
    use App\Data\Filters\OrderDataFilter;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Support\Facades\View;

    /**
     * @var Collection|User[] $clientManagersCollection
     * @var Collection|User[] $clinicalSpecialistsCollection
     * @var Collection|Doctor[] $doctorsCollection
     * @var Collection|Order[] $ordersCollection
     * @var OrderDataFilter $orderFilter
     */

    View::share('crumbs', [
        [
            'label' => __('orders.orders'),
        ]
    ]);
@endphp

@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table show-entire">
                <div class="card-body">
                    @include('pages.order.index._table-header')

                    @include('pages.order.index._table')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-app')
    {{--    <script src="{{ urlModTime('/assets/js/pages/orders/orders.js') }}"></script>--}}
    @vite('resources/js/pages/order/index.js')
@endpush
