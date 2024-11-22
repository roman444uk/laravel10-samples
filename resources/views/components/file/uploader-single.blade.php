@php
    use App\Classes\Helpers\Db\FileHelper;use App\Enums\System\FileMimeTypeEnum;use App\Models\File;use Illuminate\Database\Eloquent\Casts\Json;

    /**
     * @var array|string $mimeTypes
     * @var Illuminate\View\ComponentAttributeBag $attributes
     * @var bool $disabled
     * @var File $file
     * @var bool $instantUploading
     * @var int $maxFileSize
     * @var string $owner
     * @var int $ownerId
     * @var array $presetFiles
     * @var string $type
     * @var string $typeLabel
     * @var string $uploadId
     * @var bool $required
     */

    $mimeTypes = $mimeTypes ?? [FileMimeTypeEnum::IMAGES->value];
    $mimeTypes = is_string($mimeTypes) ? [$mimeTypes] : $mimeTypes;
    $disabled = $disabled ?? false;
    $file = $file ?? null;
    $instantUploading = $instantUploading ?? 1;
    $maxFileSize = $maxFileSize ?? 100 * 1024 * 1024;
    $required = $required ?? false;

    $presetFiles = collect($file ? [$file] : [])->map(function (File $file) {
        return [
            'id' => $file->id,
            'path' => FileHelper::getPreviewUrl($file, \App\Enums\System\FileImagePreviewSizeEnum::WIDTH_100->value),
        ];
    });

    $mergedAttributes = $attributes->only(['class', 'style'])
        ->merge([
            'class' => implode(' ', array_filter([
                'custom-file-container single',
                $disabled ? 'disabled' : '',
                $file ? 'has-files' : '',
            ]))
        ]);
@endphp

<div
        {{ $mergedAttributes }}
        data-upload-id="{{ $uploadId }}"
        data-instant-uploading="{{ $instantUploading }}"
        data-preset-files="{{ Json::encode($presetFiles) }}"
        data-max-file-size="{{ $maxFileSize ?? null }}"
        data-mime-types="{{ Json::encode($mimeTypes) }}"
        data-file-id="{{ $file?->id }}"
        data-file-type="{{ $type }}"
        data-file-owner="{{ $owner }}"
        data-file-owner-id="{{ $ownerId }}"
        data-disabled="{{ $disabled ? 1 : 0 }}"
>
    <label class="custom-file-container__custom-file-name">
        <!--Upload (Single File)-->
        <span>
            {{ $typeLabel }}
            @if($required)
                <span class="login-danger">*</span>
            @endif
        </span>
        <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a>
        {{--                <a @class(['custom-file-container__image-clear-custom', 'd-none' => !$file])--}}
        {{--                   href="javascript:void(0)"--}}
        {{--                   title="{{ __('stages.remove_photo') }}"--}}
        {{--                >x</a>--}}
        <span @class(['custom-file-container__image-multi-preview__single-image-clear'/*, 'd-none' => !$file*/])>
            <span class="custom-file-container__image-multi-preview__single-image-clear__icon">×</span>
            <!-- data-upload-token="v1awlnra7yxgiwr11921e" -->
        </span>
        @php
            $zoomEnabled = $file &&  in_array($file->ext, ['gif', 'jpg', 'jpeg', 'png']);
            $zoomHref = $zoomEnabled ? FileHelper::getFileUrl($file) : null;
        @endphp
        <a @class(['custom-file-container__image-multi-preview__single-image-zoom',
                'uploader-light-box-image' => $file,
                'd-none' => !$zoomEnabled
            ])
           href="{{ $zoomHref }}"
           data-title="{{ $file?->filename }}"
           data-description="{{ $file?->filename }}"
        >
            <span class="custom-file-container__image-multi-preview__single-image-zoom__icon">
                <i class="fa feather-zoom-in"></i>
            </span>
        </a>
    </label>
    <label class="custom-file-container__custom-file" style="display: none;">
        <input type="file" class="custom-file-container__custom-file__custom-file-input"
               accept="{{ implode(', ', $mimeTypes) }}"
        >
        <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
        <span class="custom-file-container__custom-file__custom-file-control"></span>
    </label>
    <div @class([
        'custom-file-container__image-preview',
        'custom-file-container__image-preview--active' => $file,
        'uploading' => false,
    ])>
        <div class="uploading-progress"></div>
    </div>
</div>
