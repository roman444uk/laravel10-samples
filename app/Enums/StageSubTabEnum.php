<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum StageSubTabEnum: string
{
    use EnumTrait;

    case UPLOAD_SCAN = 'upload-scan';
    case TAKE_CASTS = 'take-casts';
}
