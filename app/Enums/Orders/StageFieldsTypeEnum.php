<?php

namespace App\Enums\Orders;

use App\Traits\EnumTrait;

enum StageFieldsTypeEnum: string
{
    use EnumTrait;

    case AT_LABORATORY_DISCRETION = 'at-laboratory-discretion';
    case QUESTIONNAIRE = 'questionnaire';
    case TEXT = 'text';
}
