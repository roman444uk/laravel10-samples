<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageCrossRatioEnum: string
{
    use EnumTrait;

    case IN_ANTERIOR_REGION = 'in-anterior-region';
    case IN_LATERAL_REGION = 'in-lateral-region';
    case ONE_SIDED = 'one-sided';
    case TWO_SIDED = 'two-sided';
}
