<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageSagittalPlaneCorrectionEnum: string
{
    use EnumTrait;

    case DO_NOT_CHANGE = 'do-not-change';
    case ORTHOGNATHIC_SURGERY = 'orthognathic-surgery';
    case BYTE_JUMP_OFFSET = 'byte-jump-offset';
    case DISTALIZATION = 'distalization';
    case MESIALIZATION = 'mesialization';
}
