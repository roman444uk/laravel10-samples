<?php

namespace App\Services\Db\System;

use App\Classes\Helpers\Db\AwsS3Helper;
use App\Classes\Helpers\Db\FileHelper;
use App\Classes\ServicesResponses\OperationResponse;
use App\Enums\System\FileImagePreviewSizeEnum;
use App\Enums\System\FileOwnerEnum;
use App\Enums\System\FileSystemDiscEnum;
use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class FileService
{
    /**
     * Stores uploaded file.
     */
    public function storeUploadedFile(
        UploadedFile|string $uploadedFile, Model $owner, string $type = null, int $userId = null
    ): OperationResponse
    {
        if (is_string($uploadedFile)) {
            $uploadedFileInfo = parse_url($uploadedFile);
            $paths = explode('/', $uploadedFileInfo['path']);
            $fileName = $paths[count($paths) - 1];

            $s3Path = AwsS3Helper::prepareAccessUrl($uploadedFile);
        } else {
            $storageDisk = env('FILESYSTEM_DISK', FileSystemDiscEnum::PUBLIC->value);

            $fileName = date('Y-m-d-H-i:s-') . $uploadedFile->getClientOriginalName();
            $filePath = FileHelper::getUploadPathByOwner($owner, $type) . '/' . $fileName;

            if ($storageDisk === FileSystemDiscEnum::PUBLIC->value) {
                $put = Storage::disk('public')->put('/' . $filePath, file_get_contents($uploadedFile->getPathname()));

                if (!$put) {
                    return errorOperationResponse();
                }
            }

            $s3Path = null;
            if ($storageDisk === FileSystemDiscEnum::S3->value) {
                try {
                    $s3Path = Storage::disk()->putFile($filePath, $uploadedFile, [
                        'visibility' => 'public'
                    ]);
                } catch (\Exception $e) {
                    return errorOperationResponse($e->getMessage());
                }

                $s3Path = AwsS3Helper::prepareAccessUrl($s3Path);
            }
        }

        $file = new File();
        $file->fill([
            'user_id' => $userId,
            'filename' => $fileName,
//            'ext' => $uploadedFile->getExtension() ?: FileHelper::extractFileExt($fileName),
            'ext' => FileHelper::extractFileExt($fileName),
            'owner' => FileHelper::getOwnerAliasByModel($owner),
            'owner_id' => $owner->id,
            'type' => $type,
            'data->s3_path' => $s3Path,
            'data->size' => $uploadedFile instanceof UploadedFile ? FileHelper::getUploadedFileSize($uploadedFile) : null,
            'previews' => [],
        ]);

        if (!$file->save()) {
            return errorOperationResponse();
        }

        $this->generateDefaultPreviews($file, $uploadedFile);

        return successOperationResponse(['file' => $file]);
    }

    /**
     * Stores uploaded files.
     */
    public function storeUploadedFiles(Collection|array $files, Model $owner, string $type = null, int $userId = null): void
    {
        foreach ($files as $uploadedFile) {
            $this->storeUploadedFile($uploadedFile, $owner, $type, $userId);
        }
    }

    /**
     * Removes file from DB and from file storage.
     */
    public function destroy(File $file = null): OperationResponse
    {
        if ($file && !$file->delete()) {
            return errorOperationResponse();
        }

        if ($file) {
            $this->deleteFileFromStorage($file);
        }

        return successOperationResponse();
    }

    public function destroyBulk(Collection|array $files): OperationResponse
    {
        foreach ($files as $file) {
            $this->destroy($file);
        }

        return successOperationResponse();
    }

    /**
     * Removes file from DB and from file storage by files owner credentials.
     */
    public function destroyByOwnerCredentials(string $ownerAlias, int $ownerId, string $type, int $index = null): OperationResponse
    {
        $owner = FileHelper::getModelByOwnerAliasAndId($ownerAlias, $ownerId);
        $relationMethod = Str::toCamelCase($type);

        if ($owner->$relationMethod() instanceof HasOne) {
            $file = $owner->$relationMethod;
        }

        if ($owner->$relationMethod() instanceof HasMany) {
            $file = $owner->$relationMethod[$index];
        }

        return $file ? $this->destroy($file) : successOperationResponse();
    }

    /**
     * Remove file from storage.
     */
    public function deleteFileFromStorage(File $file): OperationResponse
    {
        // Remove file from S3 storage
        if (!empty($file->data->s3_path)) {
            try {
                $deleted = Storage::disk(FileSystemDiscEnum::S3->value)->delete($file->data->s3_path);
            } catch (\Exception $e) {
                return errorOperationResponse($e->getMessage());
            }

            collect($file->previews)->each(function (array $preview) use ($file) {
                if ($preview['s3_path']) {
                    try {
                        $deleted = Storage::disk(FileSystemDiscEnum::S3->value)->delete($preview['s3_path']);
                    } catch (\Exception $e) {
                        return errorOperationResponse($e->getMessage());
                    }
                }
            });

            return $deleted ? successOperationResponse() : errorOperationResponse();
        }

        // Otherwise remove local storage
        Storage::disk('public')->delete(
            '/' . FileHelper::getUploadPathByOwnerAliasAndIdAndProperty($file->owner, $file->owner_id, $file->type) . '/' . $file->filename
        );

        return successOperationResponse();
    }

    /**
     * Generate systems needed default previews for image files.
     */
    public function generateDefaultPreviews(File $file, UploadedFile|string $uploadedFile): void
    {
        if (!FileHelper::isImage($file) || is_string($uploadedFile)) {
            return;
        }

        if ($file->owner === FileOwnerEnum::CHAT_MESSAGE->value) {
            $this->getPreviewS3($file, FileImagePreviewSizeEnum::WIDTH_237->value, $uploadedFile->getPathname());
        }

        if ($file->owner === FileOwnerEnum::STAGE->value) {
            $this->getPreviewS3($file, FileImagePreviewSizeEnum::WIDTH_100->value, $uploadedFile->getPathname());
        }
    }

    /**
     * Image preview.
     */
    public function getPreview(string $path, int $width = null, int $height = null): string
    {
        $previewPath = $width || $height ? preview_path($width . 'x' . $height . '/' . trim($path, '/')) : $path;

        if (!file_exists($previewPath)) {
            $info = pathinfo($previewPath);

            if (!is_dir($info['dirname'])) {
                mkdir($info['dirname'], 0777, true);
            }

            ImageManager::imagick()->read(public_path($path))->scale($width, $height)->save($previewPath);
        }

        return $previewPath;
    }

    /**
     * Image S3 preview.
     */
    public function getPreviewS3(File $file, string $previewPrefix = null, string $fileLocalPath = null): OperationResponse
    {
        $filePath = FileHelper::getFileUploadedPath($file);
        $previewPath = $previewPrefix . '/' . $filePath;

        list($width, $height) = explode('x', $previewPrefix);
        $width = (int)$width;
        $height = (int)$height;

        // generate preview if not exists yet
        if (!isset($file->previews[$previewPrefix])) {
            $previewTmpPath = '/tmp/' . Str::random() . '.' . $file->ext;

            // load file from S3 if there is no local one
            if (!$fileLocalPath) {
                $fileLocalPath = '/tmp/' . Str::random() . '.' . $file->ext;

                $response = file_get_contents(FileHelper::getFileUrl($file), FALSE, stream_context_create([
                    'http' => [
                        'method' => 'GET',
                    ],
                    'ssl' => [
                        'verify_peer'      => false,
                        'verify_peer_name' => false,
                    ]
                ]));
                file_put_contents($fileLocalPath, $response, 0777);
            }

            ImageManager::imagick()
                ->read($fileLocalPath)
                ->scale($width > 0 ? $width : null, $height > 0 ? $height : null)
                ->save($previewTmpPath);

            try {
                $previewS3Path = Storage::disk(FileSystemDiscEnum::S3->value)->putFile($previewPath, $previewTmpPath, [
                    'visibility' => 'public'
                ]);

                // remove unnecessary local files
                unlink($previewTmpPath);
                if (str_contains($fileLocalPath, '/tmp/')) {
                    unlink($fileLocalPath);
                }

                $previewS3Path = AwsS3Helper::prepareAccessUrl($previewS3Path);

                $file->update([
                    'previews' => array_merge($file->previews->toArray(), [
                        $previewPrefix => [
                            's3_path' => $previewS3Path
                        ]
                    ])
                ]);
            } catch (\Exception $e) {
                return errorOperationResponse($e->getMessage());
            }
        }

        return successOperationResponse([
            'file' => $file,
            'url' => FileHelper::getPreviewUrl($file, $previewPrefix),
        ]);
    }
}
