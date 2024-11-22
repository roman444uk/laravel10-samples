@php
    $page = 'login';
@endphp

@extends('layouts.auth')
@section('content')
    <h2>{{ __('Авторизация') }}</h2>

    <!-- Form -->
    <form action="{{ route('login') }}" method="POST" id="login-form">
        @csrf

        <div class="form-group">
            <x-form.input id="email"
                          type="text"
                          name="email"
                          :label="__('users.email')"
                          :value="old('email')"
                          :required="true"
                          placeholder="example@example.com"
            ></x-form.input>
        </div>

        <div class="form-group">
            <x-form.input id="password"
                          class="pass-input"
                          type="password"
                          name="password"
                          :label="__('users.password')"
                          :required="true"
                          input-icon="profile-views feather-eye-off toggle-password"
            ></x-form.input>
        </div>

        <div class="forgotpass">
            <div class="remember-me">
                <label class="custom_check mr-2 mb-0 d-inline-flex remember-me">
                    {{ __('auth.remember_me') }}
                    <input type="checkbox" name="radio">
                    <span class="checkmark"></span>
                </label>
            </div>
            <a href="{{ url('forgot-password') }}">
                {{ __('auth.forgot_password_quest') }}
            </a>
        </div>
        <div class="form-group login-btn">
            <button class="btn btn-primary btn-block" id="login-form-button" type="submit">
                {{ __('auth.login') }}
            </button>
        </div>
    </form>
    <!-- /Form -->

    <div class="next-sign">
        <p class="account-subtitle">
            {{ __('auth.need_account_quest') }}
            <a href="{{ url('register') }}">
                {{ __('auth.register') }}
            </a>
        </p>
        @include('auth._social-login')
    </div>
@endsection

@push('scripts-app')
    @vite('resources/js/pages/auth/login.js')
@endpush
