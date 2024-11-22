@php
    use App\Enums\Orders\Stage\StageFileTypeEnum;use App\Enums\System\FileMimeTypeEnum;use App\Enums\System\FileOwnerEnum;use App\Models\Stage;

    /**
     * @var Stage $stage
     */
@endphp

<div class="form-heading">
    <h4 class="text-center fw-bold mt-3">{{ __('stages.mandatory_photos_and_snapshots') }}</h4>
</div>

@include('pages.order.form.tabs._photos-single', [
    'fileTypes' => collect(StageFileTypeEnum::getTranslationMap('stages.file_type_enums', 'casesPhotos'))
])

<div class="form-heading  mt-5">
    <h4 class="text-center fw-bold">{{ __('stages.additional_photos_and_snapshots') }}</h4>
</div>

<x-file.uploader-multiple :upload-id="StageFileTypeEnum::ADDITIONAL->value . '-' . $stage->id"
                          :owner="FileOwnerEnum::STAGE->value"
                          :owner-id="$stage->id"
                          :type="StageFileTypeEnum::ADDITIONAL->value"
                          :preset-files="$stage->photosAdditional"
                          :mime-types="[FileMimeTypeEnum::IMAGES->value, FileMimeTypeEnum::VIDEOS->value]"
                          :disabled="$stageEditionDisabled"
></x-file.uploader-multiple>
