<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.png">
    <title>Preclinic - Medical & Hospital - Bootstrap 5 Admin Template</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @include('layouts.partials.head')
</head>
<body @class(['error-pages' => Route::is(['error-404', 'error-500'])])>
<div @class([
        'main-wrapper' => !Route::is(['change-password2', 'confirm-mail','error-404','error-500','forgot-password','login','lock-screen','register', 'first-login-new-password.create', 'first-login-new-password.update']),
        'main-wrapper login-body' => Route::is(['change-password2', 'confirm-mail','forgot-password','login','lock-screen','register', 'first-login-new-password.create', 'first-login-new-password.update']),
    ])>
    @if(Route::is(['error-404','error-500']))
        <div class="main-wrapper error-wrapper">
            @endif

            @if (!Route::is(['change-password2', 'confirm-mail','password.request','login','lock-screen','register','error-404','error-500', 'first-login-new-password.create', 'first-login-new-password.update']))
                @include('layouts.partials.header')
                @include('layouts.partials.sidebar')
            @endif
            <div class="container-fluid px-0">
                <div class="row">
                    <!-- Login logo -->
                    <div class="col-lg-6 login-wrap">
                        <div class="login-sec">
                            <div class="log-img">
                                <img class="img-fluid" src="{{ urlModTime('/assets/img/login-02.png') }}" alt="Logo">
                            </div>
                        </div>
                    </div>
                    <!-- /Login logo -->
                    <!-- Login Content -->
                    <div class="col-lg-6 login-wrap-bg">
                        <div class="login-wrapper">
                            <div class="loginbox">
                                <div class="login-right">
                                    <div class="login-right-wrap">
                                        <div class="account-logo">
                                            <a href="{{ url('/') }}">
                                                <img src="{{ urlModTime('/assets/img/logo.png') }}" alt="">
                                            </a>
                                        </div>

                                        @yield('content')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Login Content -->
                </div>
            </div>
        </div>
        @component('components-forest.modal-popup')
        @endcomponent
        <div class="sidebar-overlay" data-reff=""></div>
@include('layouts.partials.footer-scripts')
</body>
</html>
