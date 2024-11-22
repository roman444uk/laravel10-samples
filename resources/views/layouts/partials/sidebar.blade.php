<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                @can('viewAny', \App\Models\User::class)
                    <li>
                        <a href="{{ route('user.index') }}" @class([
                            'active' => Request::routeIs(['user.index', 'user.edit', 'user.create'])
                        ])>
                            <span class="menu-side">
                                <img src="{{ urlModTime('/assets/img/icons/menu-icon-01.svg') }}" alt="">
                            </span>
                            <span>{{ __('users.users') }}</span>
                        </a>
                    </li>
                @endcan

                @can('viewAny', \App\Models\Order::class)
                    <li>
                        <a href="{{ route('order.index') }}" @class([
                            'active' => Request::routeIs(['order.index', 'order.edit'])
                        ])>
                            <span class="menu-side">
                                <img src="{{ urlModTime('/assets/img/icons/menu-icon-01.svg') }}" alt="">
                            </span>
                            <span>{{ __('orders.orders') }}</span>
                        </a>
                    </li>
                @endcan

                @can('viewAny', \App\Models\Chat::class)
                    <li>
                        <a href="{{ route('chat.index') }}" @class([
                            'active' => Request::routeIs(['chat.index'])
                        ])>
                            <span class="menu-side">
                                <img src="{{ urlModTime('/assets/img/icons/menu-icon-01.svg') }}" alt="">
                            </span>
                            <span>{{ __('menu.chat') }}</span>
                        </a>
                    </li>
                @endcan

                <li>
                    <a href="{{ route('web-viewer.index') }}" @class([
                        'active' => Request::routeIs(['web-viewer.index'])
                    ])>
                        <span class="menu-side">
                            <img src="{{ urlModTime('/assets/img/icons/menu-icon-01.svg') }}" alt="">
                        </span>
                        <span>{{ __('Web viewer') }}</span>
                    </a>
                </li>
            </ul>
            <div class="logout-btn">
                <a href="#" data-link="{{ route('logout') }}" data-method="post">
                    <span class="menu-side">
                        <img src="{{ urlModTime('/assets/img/icons/logout.svg') }}" alt="">
                    </span>
                    <span>{{ __('auth.logout') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
