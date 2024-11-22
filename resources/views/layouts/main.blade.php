<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.png">
    <title>{{ !empty($crumbs) ? $crumbs[count($crumbs)-1]['label'] : '' }}</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @include('layouts.partials.head')
</head>

@if (!Route::is(['error-404', 'error-500']))
    @php
        $miniSidebar = \Illuminate\Support\Facades\Request::routeIs([
            'order.edit'
        ]);
    @endphp
    <body @class(['mini-sidebar' => $miniSidebar])>
    @endif
    @if (Route::is(['error-404', 'error-500']))

        <body class="error-pages">
        @endif
        @if (!Route::is(['change-password2', 'confirm-mail','error-404','error-500','forgot-password','login','lock-screen','register']))
            <div class="main-wrapper">
                @endif
                @if (Route::is(['change-password2', 'confirm-mail','forgot-password','login','lock-screen','register']))
                    <div class="main-wrapper login-body">
                        @endif
                        @if(Route::is(['error-404','error-500']))
                            <div class="main-wrapper error-wrapper">
                                @endif
                                @if (!Route::is(['change-password2', 'confirm-mail','forgot-password','login','lock-screen','register','error-404','error-500']))
                                    @include('layouts.partials.header')
                                    @include('layouts.partials.sidebar')
                                @endif
                                <div class="page-wrapper">
                                    <div class="content">
                                        @if(!empty($crumbs))
                                            @component('components-forest.page-header', ['crumbs' => $crumbs])
                                            @endcomponent
                                        @endif

                                        @yield('content')
                                    </div>
                                    @component('components-forest.notification-box')
                                    @endcomponent
                                </div>
                                @component('components-forest.notification-box')
                                @endcomponent
                            </div>
                            @component('components-forest.modal-popup')
                            @endcomponent
                            <div class="sidebar-overlay" data-reff=""></div>
            @include('layouts.partials.footer-scripts')
        </body>

</html>
