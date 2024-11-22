<?php

namespace App\Enums\Orders;

use App\Traits\EnumTrait;

enum PatientGenderEnum: string
{
    use EnumTrait;

    case FEMALE = 'female';
    case MALE = 'male';
}
