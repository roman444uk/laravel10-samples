@php
    $page = 'login';
@endphp

@extends('layouts.auth')
@section('content')
    <h2>{{ __('auth.enter_new_password') }}</h2>

    <!-- Form -->
    <form id="first-login-new-password" action="{{ route('first-login-new-password.update') }}" method="POST">
        @csrf

        <div class="form-group">
            <x-form.input id="password-old"
                          class="pass-input"
                          type="text"
                          name="password_old"
                          :label="__('auth.old_password')"
                          :required="true"
                          input-icon="profile-views feather-eye-off toggle-password"
            ></x-form.input>
        </div>

        <div class="form-group">
            <x-form.input id="password"
                          class="pass-input"
                          type="password"
                          name="password"
                          :label="__('auth.new_password')"
                          :required="true"
                          input-icon="profile-views feather-eye-off toggle-password"
            ></x-form.input>
        </div>

        <div class="form-group">
            <x-form.input id="password-confirmation"
                          class="pass-input"
                          type="password"
                          name="password_confirmation"
                          :label="__('users.password_confirmation')"
                          :required="true"
                          input-icon="profile-views feather-eye-off toggle-password"
            ></x-form.input>
        </div>

        {{--        <div class="forgotpass">--}}
        {{--            <div class="remember-me">--}}
        {{--                <label class="custom_check mr-2 mb-0 d-inline-flex remember-me">--}}
        {{--                    {{ __('auth.remember_me') }}--}}
        {{--                    <input type="checkbox" name="radio">--}}
        {{--                    <span class="checkmark"></span>--}}
        {{--                </label>--}}
        {{--            </div>--}}
        {{--            <a href="{{ url('forgot-password') }}">--}}
        {{--                {{ __('auth.forgot_password_quest') }}--}}
        {{--            </a>--}}
        {{--        </div>--}}
        <div class="form-group login-btn">
            <button class="btn btn-primary btn-block" type="submit" id="first-login-new-password-button">
                {{ __('buttons.save') }}
            </button>

            <a class="btn btn-primary btn-block mt-4" data-link="{{ route('logout') }}" data-method="post"   >
                {{ __('auth.logout') }}
            </a>
        </div>
    </form>
    <!-- /Form -->

    {{--    <div class="next-sign">--}}
    {{--        <p class="account-subtitle">--}}
    {{--            {{ __('auth.need_account_quest') }}--}}
    {{--            <a href="{{ url('register') }}">--}}
    {{--                {{ __('auth.register') }}--}}
    {{--            </a>--}}
    {{--        </p>--}}
    {{--        @include('auth._social-login')--}}
    {{--    </div>--}}
@endsection

@push('scripts-app')
        @vite('resources/js/pages/auth/first-login-new-password.js')
@endpush
