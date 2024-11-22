<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageSagittalPlaneCorrectionGroupVergareTypeEnum: string
{
    use EnumTrait;

    case IZC = 'izc';
    case BUCCAL_SHELF = 'buccal-shelf';
}
