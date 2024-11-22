@php
    use App\Classes\Helpers\Db\FileHelper;use App\Enums\System\FileMimeTypeEnum;use App\Models\File;use Illuminate\Database\Eloquent\Casts\Json;

    /**
     * @var array|string $mimeTypes
     * @var bool $disabled
     * @var bool $instantUploading
     * @var int $maxFileSize
     * @var string $owner
     * @var int $ownerId
     * @var Illuminate\Database\Eloquent\Collection|File[]|array $presetFiles
     * @var string $type
     * @var string $typeLabel
     * @var string $uploadId
     */

    $mimeTypes = $mimeTypes ?? [FileMimeTypeEnum::IMAGES->value];
    $mimeTypes = is_string($mimeTypes) ? [$mimeTypes] : $mimeTypes;
    $disabled = $disabled ?? false;
    $instantUploading = $instantUploading ?? 1;
    $maxFileSize = $maxFileSize ?? 100 * 1024 * 1024;

    $presetFiles = collect($presetFiles)->map(function (File $file) {
        return [
            'id' => $file->id,
            'path' => FileHelper::getFileUrl($file),
            'filename' => $file->filename,
            'previews' => collect($file->previews)->map(function (array $data, $previewName) use ($file) {
                return [
                    'url' => FileHelper::getPreviewUrl($file, $previewName)
                ];
            })->toArray(),
        ];
    });
@endphp

<div @class(['custom-file-container multiple', 'disabled' => $disabled])
     data-upload-id="{{ $uploadId }}"
     data-instant-uploading="{{ $instantUploading }}"
     data-preset-files="{{ Json::encode($presetFiles) }}"
     data-max-file-size="{{ $maxFileSize ?? null }}"
     data-mime-types="{{ Json::encode($mimeTypes) }}"
     data-file-type="{{ $type }}"
     data-file-owner="{{ $owner }}"
     data-file-owner-id="{{ $ownerId }}"
     data-disabled="{{ $disabled ? 1 : 0 }}"
>
    <label>
        Upload (Allow Multiple)
        <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a>
    </label>
    <label class="custom-file-container__custom-file">
        <input type="file" class="custom-file-container__custom-file__custom-file-input"
               accept="{{ implode(', ', $mimeTypes) }}"
               multiple
        >
        <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
        <span class="custom-file-container__custom-file__custom-file-control"></span>
    </label>
    <div class="custom-file-container__image-preview"></div>
</div>
