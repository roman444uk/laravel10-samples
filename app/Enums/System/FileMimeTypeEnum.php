<?php

namespace App\Enums\System;

enum FileMimeTypeEnum: string
{
    case APPLICATIONS = 'application/*';
    case AUDIOS = 'audio/*';
    case IMAGES = 'image/*';
    case VIDEOS = 'video/*';

    case APPLICATION_PDF = 'application/pdf';
    case APPLICATION_ZIP = 'application/zip';

    case IMAGE_PNG = 'image/png';
    case IMAGE_JPEG = 'image/jpeg';
    case IMAGE_JPG = 'image/jpg';
}
