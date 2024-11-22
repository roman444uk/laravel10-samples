@php
    use App\Classes\Helpers\Db\NotificationHelper;
    use App\Support\Utilities\DateTime;
    use Illuminate\Support\Facades\View;

    /**
     * @var Illuminate\Database\Eloquent\Collection|Illuminate\Notifications\DatabaseNotification[] $notificationsCollection
     */

    View::share('crumbs', [
        [
            'label' => __('notifications.notifications'),
        ]
    ]);
@endphp

@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table show-entire">
                <div class="card-body">
                    <div class="dataTables_wrapper pt-4">
                        <div class="activity">
                            <div class="activity-box">
                                <ul class="activity-list">
                                    @foreach($notificationsCollection as $notification)
                                        <x-list.activity-item :date="DateTime::renderDateTime($notification->created_at, 'd.m.Y')"
                                                              :time="DateTime::renderTime($notification->created_at)"

                                        >
                                            {!! NotificationHelper::message($notification) !!}
                                        </x-list.activity-item>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <x-paginate-footer :collection="$notificationsCollection"></x-paginate-footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
