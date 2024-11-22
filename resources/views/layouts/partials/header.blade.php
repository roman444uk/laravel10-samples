@php
    use App\Classes\Helpers\Db\UserHelper;
    use App\Models\Repositories\NotificationRepository;
    use App\Support\Str;

    $notificationRepository = app()->make(NotificationRepository::class);

    $notificationsPaginator = $notificationRepository->paginate(\App\Data\Filters\NotificationDataFilter::from([
        'pageSize' => 5,
        'notifiableId' => getUserId(),
    ]));

    $unreadNotificationsCount = $notificationRepository->getUnreadNotificationCount(getUserId());
@endphp

<div class="header">
    <div class="header-left">
        <a href="{{ url('/') }}" class="logo">
            <img src="{{ urlModTime('/assets/img/logo-left.png') }}" width="50px" alt="">
            <img src="{{ urlModTime('/assets/img/logo-right.png') }}" width="120px" alt="">
        </a>
    </div>
    <a id="toggle_btn" href="javascript:void(0);">
        &nbsp;
        <img src="{{ urlModTime('/assets/img/icons/bar-icon.svg') }}" alt="">
    </a>
    <a id="mobile_btn" class="mobile_btn float-start" href="#sidebar">
        <img src="{{ urlModTime('/assets/img/icons/bar-icon.svg') }}" alt="">
    </a>
    <div class="top-nav-search mob-view">
        <form action="javascript:;">
            <input type="text" class="form-control" placeholder="Search here">
            <a class="btn">
                <img src="{{ urlModTime('/assets/img/icons/search-normal.svg') }}" alt="">
            </a>
        </form>
    </div>
    <ul class="nav user-menu float-end">
        <li class="nav-item dropdown d-none d-md-block">
            <a href="javascript:;" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <img src="{{ urlModTime('/assets/img/icons/note-icon-01.svg') }}" alt="">
                <span @class(['pulse pulse-notifications', 'd-none' => $unreadNotificationsCount === 0])"></span>
            </a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span>{{ __('notifications.notifications') }}</span>
                </div>
                <div class="drop-scroll">
                    <ul class="notification-list">
                        @foreach($notificationsPaginator as $notification)
                            <li data-id="{{ $notification->id }}"
                                @class([
                                    'notification-message',
                                    'notification-read cursor-pointer' => empty($notification->read_at),
                                    'unread' => empty($notification->read_at),
                                ])
                            >
                            <span>
                                    <div class="media">
                                        {{--                                    <span class="avatar">--}}
                                        {{--                                        <img alt="John Doe" src="{{ urlModTime('/assets/img/user.jpg') }}"--}}
                                        {{--                                             class="img-fluid">--}}
                                        {{--                                    </span>--}}
                                        <div class="media-body">
                                            <p class="noti-details">
                                                    {!! \App\Classes\Helpers\Db\NotificationHelper::message($notification, false) !!}
                                                {{--                                            <span class="noti-title">John Doe</span> added new task--}}
                                                {{--                                            <span class="noti-title">Patient appointment booking</span>--}}
                                            </p>
                                            <p class="noti-time">
                                                <span class="notification-time">
                                                    {{ \App\Support\Utilities\DateTime::renderDateTime($notification->created_at) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="{{ route('notification.index') }}">
                        {{ __('notifications.view_all_notifications') }}
                    </a>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown d-none d-md-block">
            <a href="javascript:void(0);" id="open_msg_box" class="hasnotifications nav-link">
                <img src="{{ urlModTime('/assets/img/icons/note-icon-02.svg') }}" alt="">
                <span @class(['pulse', 'd-none' => true])></span>
            </a>
        </li>
        <li class="nav-item dropdown has-arrow user-profile-list">
            <a href="javascript:;" class="dropdown-toggle nav-link user-link" data-bs-toggle="dropdown">
                <div class="user-names">
                    <h5>
                        {{ getProfile()->full_name }}
                    </h5>
                    <span>{{ __('users.role_enums.' . Str::delimiterFromDashToUnderscore(getUserRole())) }}</span>
                </div>
                <span class="user-img">
                    <img src="{{ urlModTime('/assets/img/user-06.jpg') }}" alt="Admin">
                </span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    {{ __('users.profile') }}
                </a>
                <a class="dropdown-item" href="#" data-link="{{ route('logout') }}" data-method="post">
                    {{ __('auth.logout') }}
                </a>
            </div>
        </li>
        <li class="nav-item ">
            <a href="{{ url('settings') }}" class="hasnotifications nav-link"><img
                    src="{{ urlModTime('/assets/img/icons/setting-icon-01.svg') }}" alt=""> </a>
        </li>
    </ul>
    <div class="dropdown mobile-user-menu float-end">
        <a href="javascript:;" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i
                class="fa-solid fa-ellipsis-vertical"></i></a>
        <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item" href="{{ url('profile') }}">My Profile</a>
            <a class="dropdown-item" href="{{ url('edit-profile') }}">Edit Profile</a>
            <a class="dropdown-item" href="{{ url('settings') }}">Settings</a>
            <a class="dropdown-item" href="{{ url('login') }}">Logout</a>
        </div>
    </div>
</div>
