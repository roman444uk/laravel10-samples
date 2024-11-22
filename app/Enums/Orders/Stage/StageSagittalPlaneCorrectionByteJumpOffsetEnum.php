<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageSagittalPlaneCorrectionByteJumpOffsetEnum: string
{
    use EnumTrait;

    case USE_BITE_REGISTER = 'use-bite-register';
    case ANTERIOR_OFFSET = 'anterior-offset';
    case LATERAL_OFFSET = 'lateral-offset';
    case OFFSET_WITH_ROTATION = 'offset-with-rotation';
    case VERTICAL_OFFSET = 'vertical-offset';
}
