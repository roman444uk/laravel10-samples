<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageCentralLineOffsetJawEnum: string
{
    use EnumTrait;

    case ON_TOP_JAW = 'on-top-jaw';
    case ON_BOTTOM_JAW = 'on-bottom-jaw';
}
