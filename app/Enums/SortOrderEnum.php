<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum SortOrderEnum: string
{
    use EnumTrait;

    case ASC = 'asc';
    case DESC = 'desc';
}
