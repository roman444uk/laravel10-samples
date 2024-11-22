@php
    use App\Classes\Helpers\Db\FileHelper;use App\Models\DoctorClinic;

    /**
     * @var string $action
     * @var bool $doctorDetailsRequired
     * @var string $title
     * @var App\Models\User $user
     */

    $editionDisabled = false;

    $jsData = [
        'user' => $user?->toArray()
    ];
@endphp

    <!-- /Page Header -->
<form action="{{ $action }}" id="user-form" method="POST">
    <div class="card-box">
        <h3 class="card-title">
            {{ __('common.basic_information') }}
        </h3>
        <div class="row">
            <div class="col-md-12">
                <div class="profile-img-wrap">
                    <img class="inline-block"
                         src="{{ $user->profile->fileAvatar ? FileHelper::getFileUrl($user->profile->fileAvatar) :  urlModTime('/assets/img/user.jpg') }}"
                         alt="user"
                    >
                    <div class="fileupload btn">
                            <span class="btn-text">
                                {{ __('common.change') }}
                            </span>
                        <input class="upload" type="file"
                               name="profile[{{ \App\Enums\Users\ProfileFileTypeEnum::AVATAR->value }}]">
                    </div>
                </div>

                <div class="profile-basic">
                    @include('pages.user.form._basic')
                </div>
            </div>
        </div>
    </div>

    @if(isUserDoctor($user))
        @include('pages.user.form._doctor', [
            'doctorDetailsRequired' => $doctorDetailsRequired
        ])
    @endif

    <div class="text-center">
        <x-button type="button"
                  class="btn btn-primary submit-btn mb-4"
                  id="user-form-button"
                  :loadable="true"
        >
            {{ __('buttons.save') }}
        </x-button>
    </div>
</form>


<div id="doctor-clinic-tmpl" class="d-none">
    @include('pages.user.form._doctor-clinic', [
        'doctorClinic' => new DoctorClinic(),
    ])
</div>

@push('scripts-app')
    @vite('resources/js/pages/user/edit.js')
@endpush
