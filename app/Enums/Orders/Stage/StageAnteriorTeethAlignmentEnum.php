<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageAnteriorTeethAlignmentEnum: string
{
    use EnumTrait;

    case ALONG_CUTTING_EDGE = 'along-cutting-edge';
    case ALONG_GINGIVAL_EDGE = 'along-gingival-edge';
}
