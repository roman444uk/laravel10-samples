<?php

namespace App\Classes\Helpers\Db;

use App\Enums\System\FileOwnerEnum;
use App\Models\ChatMessage;
use App\Models\Check;
use App\Models\Doctor;
use App\Models\File;
use App\Models\Order;
use App\Models\Production;
use App\Models\Profile;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class FileHelper
{
    /**
     * Returns owner model alias by model instance
     */
    public static function getOwnerAliasByModel(Model|string $model): ?string
    {
        return match ($model instanceof Model ? $model::class : $model) {
            Check::class => FileOwnerEnum::CHECK->value,
            Doctor::class => FileOwnerEnum::DOCTOR->value,
            ChatMessage::class => FileOwnerEnum::CHAT_MESSAGE->value,
            Order::class => FileOwnerEnum::ORDER->value,
            Production::class => FileOwnerEnum::PRODUCTION->value,
            Profile::class => FileOwnerEnum::PROFILE->value,
            Stage::class => FileOwnerEnum::STAGE->value,
            User::class => FileOwnerEnum::USER->value,
            default => null,
        };
    }

    /**
     * Returns model instance by file instance.
     */
    public static function getModelOwner(File $file): ?Model
    {
        return self::getModelByOwnerAliasAndId($file->owner, $file->owner_id);
    }

    /**
     * Returns model instance by owner alias and owner ID.
     */
    public static function getModelByOwnerAliasAndId(string $ownerAlias, string $ownerId): ?Model
    {
        return match ($ownerAlias) {
            FileOwnerEnum::DOCTOR->value => Doctor::firstWhere('id', $ownerId),
            FileOwnerEnum::ORDER->value => Order::firstWhere('id', $ownerId),
            FileOwnerEnum::STAGE->value => Stage::firstWhere('id', $ownerId),
            default => null,
        };
    }

    /**
     * Returns upload path by model instance and it's property
     */
    public static function getUploadPathByOwner(Model $owner, string $property = null): ?string
    {
        return self::getUploadPathByOwnerAliasAndIdAndProperty(self::getOwnerAliasByModel($owner), $owner->id, $property);
    }

    /**
     * Returns upload path by owner model alias and it's property
     */
    public static function getUploadPathByOwnerAliasAndIdAndProperty(string $ownerAlias, int $ownerId, string $property = null): ?string
    {
        return $ownerAlias . ($property ? '/' . $property : '') . '/' . $ownerId;
    }

    public static function getFileUrl(File|\stdClass $file): string
    {
        if (!empty($file->data->s3_path)) {
            return AwsS3Helper::getFileUrl($file->data->s3_path);
        }

        return '/storage/' . self::getUploadPathByOwnerAliasAndIdAndProperty($file->owner, $file->owner_id, $file->type) . '/' . $file->filename;
    }

    public static function getPreviewUrl(File|\stdClass $file, string $preview): ?string
    {
        if (!empty($file->previews[$preview])) {
            return AwsS3Helper::getFileUrl($file->previews[$preview]['s3_path']);
        }

        return null;
    }

    /**
     * Original path to file depends on owner model.
     */
    public static function getFileUploadedPath(File $file): string
    {
        return self::getUploadPathByOwnerAliasAndIdAndProperty($file->owner, $file->owner_id, $file->type) . '/' . $file->filename;
    }

    public static function getFilePath(File $file): string
    {
        if (!empty($file->data->s3_path)) {
            return $file->data->s3_path;
        }

        return self::getUploadPathByOwnerAliasAndIdAndProperty($file->owner, $file->owner_id, $file->type) . '/' . $file->filename;
    }

    public static function getFileSystemPath(File|\stdClass $file): string
    {
        return public_path(self::getFileUrl($file));
    }

    public static function getFileSize(File $file): ?int
    {
        if (!empty($file->data->s3_path)) {
            return $file->data->size;
        }

        return file_exists(self::getFileSystemPath($file)) ? (int)filesize(self::getFileSystemPath($file)) : null;
    }

    public static function getUploadedFileSize(UploadedFile $uploadedFile): string
    {
        return (int)filesize($uploadedFile->path());
    }

    public static function extractFileName(string $fileName): string
    {
        $firstCharPos = mb_strpos($fileName, '-') + 1;
        return mb_substr($fileName, $firstCharPos, mb_strrpos($fileName, '.') - $firstCharPos);
    }

    public static function extractFileExt(string $fileName): string
    {
        $lastDotPos = mb_strrpos($fileName, '.') + 1;
        return mb_substr($fileName, $lastDotPos);
    }

    public static function getIconByExtension($ext): string
    {
        $icon = 'document-icon.svg';

        if (in_array($ext, ['jpeg', 'jpg', 'png'])) {
            $icon = 'gallery-icon.svg';
        }

        if (in_array($ext, ['doc', 'pdf', 'txt', 'xml', 'xsl'])) {
            $icon = 'document-icon.svg';
        }

        return '/assets/img/icons/' . $icon;
    }

    /**
     * Whether file mimetype is image.
     */
    public static function isImageMimeType(string $mimeType): bool
    {
        return str_starts_with($mimeType, 'image/');
    }

    /**
     * Whether file is image.
     */
    public static function isImage(File $file): bool
    {
        return self::isImageExtension($file->ext);
    }

    /**
     * Whether file extension is image.
     */
    public static function isImageExtension(string $extension): bool
    {
        return in_array($extension, ['jpeg', 'jpg', 'png']);
    }
}
