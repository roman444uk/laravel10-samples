<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageOcclusalPlaneCorrectionSchemaEnum: string
{
    use EnumTrait;

    case INTRUSION = 'intrusion';
    case EXTRUSION = 'extrusion';
}
