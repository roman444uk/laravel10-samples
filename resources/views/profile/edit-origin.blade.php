<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <x-form.input id="specialization"
                      type="text"
                      name="specialization"
                      :value="old('specialization')"
                      :label="__('doctors.specialization')"
                      :required="true"
        ></x-form.input>
    </div>

    <div class="form-group">
        <x-form.input id="experience-from"
                      type="text"
                      name="experience_from"
                      :value="old('experience_from')"
                      :label="__('doctors.experience_from')"
                      :required="true"
        ></x-form.input>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ __('users.photo') }}</h5>
        </div>
        <div class="card-body">
            <div class="custom-file-container" data-upload-id="myFirstImage">
                <label>Upload (Single File) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                <label class="custom-file-container__custom-file" >
                    <input type="file" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                </label>
                <div class="custom-file-container__image-preview"></div>
            </div>
        </div>
    </div>
</x-app-layout>
