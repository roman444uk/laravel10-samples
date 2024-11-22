<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageWideningVolumeEnum: string
{
    use EnumTrait;

    case ONE_TWO_MM = 'one-two-mm';
    case TWO_THREE_MM = 'two-three-mm';
    case THREE_FOUR_MM = 'three-four-mm';
}
