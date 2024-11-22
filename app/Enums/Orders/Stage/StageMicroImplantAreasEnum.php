<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageMicroImplantAreasEnum: string
{
    use EnumTrait;

    case INTERVERTEBRAL = 'intervertebral';
    case GROUP = 'group';
}
