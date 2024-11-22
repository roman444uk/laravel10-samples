<?php

namespace App\Http\Controllers\System;

use App\Enums\System\SessionEnum;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class SettingController extends BaseController
{
    use AuthorizesRequests,
        ValidatesRequests;

    /**
     * Set chat offcanvas element width.
     */
    public function chatOffCanvasWidth(string $width)
    {
        session()->put(SessionEnum::CHAT_OFF_CANVAS_WIDTH->value, $width);

        return successJsonResponse();
    }

    public function S3Cors()
    {
        $bucket = 'floflex-storage';
//
        $client = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'endpoint' => env('AWS_ENDPOINT'),
        ]);

        //Listing all S3 Bucket
//        $buckets = $client->listBuckets();
//        dd($buckets);
//        die();

//        $result = $client->getBucketPolicy([
//            'Bucket' => $bucket,
//        ]);
//        dd($result);
//        die();

//        $result = $client->createBucket([
//            'Bucket' => $bucket,
//        ]);
//        dd($result);
//        die();

//        $result = $client->deleteBucketCors([
//            'Bucket' => $bucket,
//        ]);
//        dd($result);
//        die();

        try {
            $result = $client->putBucketCors([
                'Bucket' => $bucket,
                'CORSConfiguration' => [ // REQUIRED
                    'CORSRules' => [ // REQUIRED
                        [
                            'AllowedHeaders' => [
//                              'Access-Control-Allow-Origin', 'Authorization'
                                "*"
                            ],
                            'AllowedMethods' => [
                                'GET', 'HEAD', 'POST', 'PUT', 'DELETE'
                            ], // REQUIRED
                            'AllowedOrigins' => [
                                /**
                                 * Local
                                 */
                                'http://floflex.local',
                                'https://floflex.local',
                                /**
                                 * Stage
                                 */
                                'http://94.241.168.131',
                                'https://94.241.168.131',
                                'http://flo.wisesol.ru',
                                'https://flo.wisesol.ru',
                                /**
                                 * Production
                                 */
                                'http://lk.floflex.ru',
                                'https://lk.floflex.ru',

                            ], // REQUIRED
                            'ExposeHeaders' => [
                                'ETag'
                            ],
                            'MaxAgeSeconds' => 3600
                        ],
                    ],
                ]
            ]);
            dd($result);
        } catch (AwsException $e) {
            // output error message if fails
            dd($e->getMessage());
        }
        die();

        $result = $client->getBucketCors([
            'Bucket' => $bucket,
        ]);
        dd($result);
//
//        die();
    }
}
