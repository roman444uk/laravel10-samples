<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageAnteriorTeethAlignmentAlongCuttingEdgeEnum: string
{
    use EnumTrait;

    case LATERAL_INCISORS_SAME_LEVEL = 'lateral-incisors-same-level';
    case LATERAL_INCISORS_SHORTER = 'lateral-incisors-shorter';
}
