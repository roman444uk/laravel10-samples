@php
    /**
     * @var App\Models\User $user
     */

    $editionDisabled = false;

    $jsData = [
        'user' => $user?->toArray()
    ];
@endphp

@extends('layouts.main')

@section('content')
    <!-- /Page Header -->
    <form action="{{ $user?->id ? route('user.update', ['user' => $user]) : route('user.store') }}"
          method="POST"
          id="user-form"
    >
        @csrf

        <div class="card-box">
            <h3 class="card-title">
                {{ $user?->id ? __('users.user_editing') : __('users.user_creating') }}
            </h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-img-wrap">
                        <img class="inline-block"
                             src="{{ $user->fileAvatar ? FileHelper::getFileUrl($user->fileAvatar) :  urlModTime('/assets/img/user.jpg') }}"
                             alt="user"
                        >
                        <div class="fileupload btn">
                            <span class="btn-text">
                                {{ __('common.change') }}
                            </span>
                            <input class="upload" type="file"
                                   name="{{ \App\Enums\Users\ProfileFileTypeEnum::AVATAR->value }}">
                        </div>
                    </div>

                    <div class="profile-basic">
                        @include('pages.user.form._form-basic')
                    </div>
                </div>
            </div>
        </div>

        @if(isUserDoctor($user))
            @include('pages.user.form._form-doctor')
        @endif

        <div class="text-center">
            <x-button type="submit"
                      class="btn btn-primary submit-btn mb-4"
                      id="profile-form-button"
                      :loadable="true"
            >
                {{ __('buttons.save') }}
            </x-button>
        </div>
    </form>
@endsection
