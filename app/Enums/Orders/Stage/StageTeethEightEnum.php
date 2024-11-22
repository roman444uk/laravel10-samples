<?php

namespace App\Enums\Orders\Stage;

use App\Traits\EnumTrait;

enum StageTeethEightEnum: string
{
    use EnumTrait;

    case PLANNED_REMOVAL = 'planned-removal';
    case DID_NOT_PLANNED_REMOVAL = 'did-not-planned-removal';
    case REMOVAL_BY_LABORATORY_RECOMMENDATION = 'removal-by-laboratory-recommendation';
}
