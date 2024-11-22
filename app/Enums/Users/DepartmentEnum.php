<?php

namespace App\Enums\Users;

use App\Traits\EnumTrait;

enum DepartmentEnum: string
{
    use EnumTrait;

    case ADMINISTRATION = 'administration';
    case CLINICAL = 'clinical';
    case PRODUCTION = 'production';
    case LOGISTIC = 'logistic';
}
