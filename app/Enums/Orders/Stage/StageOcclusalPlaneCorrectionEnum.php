<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageOcclusalPlaneCorrectionEnum: string
{
    use EnumTrait;

    case FILL_SCHEME = 'fill-scheme';
    case AT_LABORATORY_DISCRETION = 'at-laboratory-discretion';
}
