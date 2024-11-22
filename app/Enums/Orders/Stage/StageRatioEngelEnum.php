<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageRatioEngelEnum: string
{
    use EnumTrait;

    case CLASS_ONE = 'class-one';
    case CLASS_TWO = 'class-two';
    case CLASS_THREE = 'class-three';
}
