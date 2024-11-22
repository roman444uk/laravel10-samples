<?php

namespace App\Enums\System;

enum FileSystemDiscEnum: string
{
    case LOCAL = 'local';
    case PUBLIC = 'public';
    case S3 = 's3';
}
