<?php

namespace App\Enums\System;

use App\Traits\EnumTrait;

enum FileOwnerEnum: string
{
    use EnumTrait;

    case CHAT_MESSAGE = 'chat-message';
    case CHECK = 'check';
    case DOCTOR = 'doctor';
    case ORDER = 'order';
    case PRODUCTION = 'production';
    case PROFILE = 'profile';
    case STAGE = 'stage';
    case USER = 'user';
}
