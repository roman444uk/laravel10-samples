@php
    use App\Classes\Helpers\Db\StageHelper;
    use App\Enums\Orders\Stage\StageFileTypeEnum;
    use App\Enums\System\FileMimeTypeEnum;
    use App\Enums\System\FileOwnerEnum;
    use App\Models\Stage;
    use App\Support\Str;

    /**
     * @var bool $stageEditionDisabled
     * @var Stage $stage
     * @var array $fileTypes
     */
@endphp

<div class="custom-files-container">
    @foreach($fileTypes as $propName => $propLabel)
        @php
            $uploadId = $propName . '-' . $stage->id;
            $modelProp = 'photo' . Str::toCamelCase($propName);
            $file = $stage->$modelProp ?: null;

            $required = in_array(
                $propName,
                collect(StageFileTypeEnum::getValues('casesPhotos'))
                ->merge(StageFileTypeEnum::getValues('casesScansAndImpressions'))
                ->merge(StageFileTypeEnum::getValues('casesXRayAndCt'))
                ->toArray()
            );

            if ($propName === StageFileTypeEnum::SCAN_IMPRESSION->value && StageHelper::isTakeCastsFilled($stage)) {
                $required = false;
            }
        @endphp

        <x-file.uploader-single :upload-id="$uploadId"
                                :file="$file"
                                :owner="FileOwnerEnum::STAGE->value"
                                :owner-id="$stage->id"
                                :type="$propName"
                                :type-label="$propLabel"
                                :mime-types="[FileMimeTypeEnum::IMAGES->value]"
                                :required="$required"
                                :disabled="$stageEditionDisabled"
        ></x-file.uploader-single>
    @endforeach
</div>
