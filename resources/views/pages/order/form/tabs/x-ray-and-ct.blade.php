@php
    use App\Enums\Orders\Stage\StageFileTypeEnum;use App\Models\Stage;

    /**
     * @var bool $stageEditionDisabled
     * @var Stage $stage
     */
@endphp

<div class="form-group">
    @include('pages.order.form.tabs._photos-single', [
        'fileTypes' => collect(StageFileTypeEnum::getTranslationMap('stages.file_type_enums', 'casesXRayAndCt'))
    ])
</div>

<div class="form-group local-forms stage-row">
    <x-form.input id="xray-and-ct-files-link"
                  class="floating"
                  type="text"
                  name="data[xray_and_ct_files_link]"
                  :value="$stage->data->xray_and_ct_files_link ?? ''"
                  :label="__('stages.xray_and_ct_files_link')"
                  :disabled="$stageEditionDisabled"
    ></x-form.input>
</div>
