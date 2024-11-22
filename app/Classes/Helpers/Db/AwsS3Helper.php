<?php

namespace App\Classes\Helpers\Db;

use Illuminate\Support\Facades\Storage;

class AwsS3Helper
{
    public static function prepareAccessUrl(string $url): string
    {
        $info = parse_url($url);

        return trim($info['path'], '/');
    }

    public static function getFileUrl(string $url): string
    {
        return env('AWS_URL') ?
            Storage::disk('s3')->url($url) :
            trim(env('AWS_ACCESS_URL'), '/') . '/' .trim($url, '/');
    }
}
