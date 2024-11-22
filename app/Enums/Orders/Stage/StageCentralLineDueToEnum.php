<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageCentralLineDueToEnum: string
{
    use EnumTrait;

    case TEETH_OFFSET = 'teeth-offset';
    case CHANGE_LOW_JAR_POSITION = 'change-low-jar-position';
}
