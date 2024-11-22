@php
    $page = 'login';
@endphp

@extends('layouts.auth')
@section('content')
    <h2>{{ __('Сбросить пароль') }}</h2>

    <!-- Form -->
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <x-form-input id="email"
                          type="text"
                          name="email"
                          :value="old('email')"
                          :label="__('Email')"
                          :required="true"
                          placeholder="example@example.com"
            ></x-form-input>
        </div>

        <div class="form-group login-btn">
            <button class="btn btn-primary btn-block" type="submit">
                {{ __('Сбросить пароль') }}
            </button>
        </div>
    </form>
    <!-- /Form -->

    <div class="next-sign">
        <p class="account-subtitle">
            {{ __('Нужен аккаунт?') }}
            <a href="{{ url('login') }}">{{ __('Авторизуйтесь') }}</a>
        </p>
        @include('auth._social-login')
    </div>
@endsection

@push('scripts-app')
    @vite('resources/js/pages/auth/register.js')
@endpush
