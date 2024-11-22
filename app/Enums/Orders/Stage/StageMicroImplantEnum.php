<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageMicroImplantEnum: string
{
    use EnumTrait;

    case YES = 'yes';
    case NO = 'no';
    case BY_LABORATORY_RECOMMENDATION = 'by-laboratory-recommendation';
}
