<?php

namespace App\Data;

use App\Classes\Helpers\Db\FileHelper;
use App\Models\File;
use Illuminate\Database\Eloquent\Casts\ArrayObject;

class FileData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?int                   $id,
        public ?string                $url,
        public ?string                $name,
        public ?string                $ext,
        public ?bool                  $main,
        public ArrayObject|array|null $data,
        public ArrayObject|array|null $previews,
        public ?string                $size,
        public ?string                $icon,
        public ?int                   $userId,
        public ?string                $owner,
        public ?int                   $ownerId,
    )
    {
    }

    public static function fromModel(File $file): self
    {
        return new self(
            $file->id,
            FileHelper::getFileUrl($file),
            $file->filename,
            $file->ext,
            (bool)$file->main,
            $file->data,
            collect($file->previews)->map(function (array $previewData, string $previewName) use ($file) {
                return [
                    'url' => FileHelper::getPreviewUrl($file, $previewName),
                ];
            })->toArray(),
            FileHelper::getFileSize($file),
            FileHelper::getIconByExtension($file->ext),
            $file->user_id,
            $file->owner,
            $file->owner_id,
        );
    }
}
