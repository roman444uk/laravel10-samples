<?php

namespace App\Enums\Chat;

use App\Traits\EnumTrait;

enum ChatParticipantStatusEnum: string
{
    use EnumTrait;

    case ACTIVE = 'active';
    case DISABLED = 'disabled';
    case READONLY = 'readonly';
}
