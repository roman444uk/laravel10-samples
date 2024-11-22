<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageEliminateCrowdingBy: string
{
    use EnumTrait;

    case DISTALIZATION = 'distalization';
    case PROTRUSION = 'protrusion';
    case REMOVING = 'removing';
    case SEPARATION = 'separation';
    case UP_WRITING = 'up-writing';
    case WIDENING = 'widening';
}
