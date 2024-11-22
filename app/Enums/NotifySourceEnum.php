<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum NotifySourceEnum: string
{
    use EnumTrait;

    case CHAT = 'chat';
    case CHAT_SUB_TYPE = 'chat-sub-type';
    case EMAIL_SUBJECT = 'email-subj';
    case EMAIL_BODY = 'email-body';
    case NOTIFY = 'notify';
}
