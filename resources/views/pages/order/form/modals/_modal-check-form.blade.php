@php
    use App\Enums\System\FileMimeTypeEnum;use App\Enums\System\FileOwnerEnum;

@endphp

<x-modal id="check-form-modal">
    <x-slot:title>
        {{ __('checks.adding_check') }}
    </x-slot:title>

    <x-slot:buttons>
        <x-button id="check-save-button"
                  class="btn btn-secondary"
                  type="button"
                  :loadable="true"
        >{{ __('buttons.save') }}</x-button>
    </x-slot:buttons>

    <form id="check-form">
        @csrf

        <input type="hidden" name="id" value="">

        <div class="form-group local-forms">
            <x-form.input id="steps-count"
                          class="floating doctor-clinic-input"
                          type="text"
                          name="steps_count"
                          :label="__('checks.steps_count')"
            ></x-form.input>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6 text-center">
                <div>
                    <x-file.uploader-single upload-id="check-setup-top"
                                            :instant-uploading="false"
                                            :owner="FileOwnerEnum::CHECK->value"
                                            :owner-id="null"
                                            :preset-files="[]"
                                            :mime-types="[FileMimeTypeEnum::APPLICATION_ZIP->value]"
                                            type="fileSetupTop"
                                            :type-label="__('stages.top_jaw')"
                    ></x-file.uploader-single>
                </div>

                <a id="check-setup-top-link" class="fs-7" target="_blank">
                    {{ __('files.link_to_file') }}
                </a>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6 text-center">
                <div>
                    <x-file.uploader-single :instant-uploading="false"
                                            upload-id="check-setup-bottom"
                                            :owner="FileOwnerEnum::CHECK->value"
                                            :owner-id="null"
                                            :preset-files="[]"
                                            :mime-types="[FileMimeTypeEnum::APPLICATION_ZIP->value]"
                                            type="fileSetupBottom"
                                            :type-label="__('stages.bottom_jaw')"
                    ></x-file.uploader-single>
                </div>

                <a id="check-setup-bottom-link" class="fs-7" target="_blank">
                    {{ __('files.link_to_file') }}
                </a>
            </div>
        </div>

        <div class="form-group local-forms mt-4">
            <div class="input-group">
                <x-form.input id="viewer-url"
                              class="floating doctor-clinic-input"
                              type="text"
                              name="viewer_url"
                              :label="__('checks.viewer_url')"
                ></x-form.input>
                <a class="btn btn-primary" id="viewer-url-link" class="fs-7" style="padding-top: 11px;"
                   target="_blank"
                >
                    {{ __('common.open') }}
                </a>
            </div>
        </div>
    </form>
</x-modal>
