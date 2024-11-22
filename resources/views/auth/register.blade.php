@php
    /**
     * @var string $emailConfirmationCode
     * @var string $emailConfirmationCodeAddress
     */

    $page = 'register';
@endphp

@extends('layouts.auth')
@section('content')
    <h2>{{ __('Регистрация') }}</h2>
    <!-- Form -->
    <form action="{{ route('register') }}" method="POST" id="register-form">
        @csrf

        <div class="form-group">
            <x-form.input id="name "
                          type="text"
                          name="name"
                          :value="old('full_name')"
                          :label="__('users.full_name')"
                          :required="true"
            ></x-form.input>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-6">
                <div class="form-group">
                    <x-form.input id="email"
                                  type="text"
                                  name="email"
                                  :value="old('email', $emailConfirmationCodeAddress)"
                                  :label="__('users.email')"
                                  :required="true"
                                  placeholder="example@example.com"
                    ></x-form.input>
                </div>
            </div>
            <div class="col-md-3 col-lg-3">
                <div class="form-group">
                    <x-form.input id="email-confirmation"
                                  type="text"
                                  name="email_confirmation"
                                  :value="old('email_confirmation')"
                                  :label="__('auth.verification_code')"
                                  :required="true"
                                  :disabled="!$emailConfirmationCode"
                    ></x-form.input>
                </div>
            </div>
            <div class="col-md-3 col-lg-3">
                <div class="form-group login-btn">
                    <div class="form-group login-btn">
                        <x-button type="button"
                                  class="btn-primary btn-block"
                                  id="send-code-to-email"
                                  :disabled="$emailConfirmationCode"
                                  :loadable="true"
                        >
                            {{ !$emailConfirmationCode ? __('auth.send_code') : __('auth.code_sent') }}
                        </x-button>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <x-form.input id="phone"
                          type="text"
                          name="phone"
                          :value="old('phone')"
                          :label="__('users.phone')"
                          :required="true"
                          data-input-mask="phone"
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

        <div class="form-group login-btn">
            <button class="btn btn-primary btn-block" type="submit" id="register-button">
                {{ __('Зарегистрироваться') }}
            </button>
        </div>
    </form>
    <!-- /Form -->

    <div class="next-sign">
        <p class="account-subtitle">
            {{ __('Уже есть аккаунт?') }}
            <a href="{{ url('login') }}">{{ __('Авторизуйтесь') }}</a>
        </p>
        @include('auth._social-login')
    </div>
@endsection

@push('scripts-app')
    @vite('resources/js/pages/auth/register.js')
@endpush
