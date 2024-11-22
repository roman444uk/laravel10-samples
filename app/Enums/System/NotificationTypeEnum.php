<?php

namespace App\Enums\System;

enum NotificationTypeEnum: string
{
    case CHECK_VERIFICATION_ACTION = 'check-verification-action';

    case ORDER_CREATED = 'order-created';
    case ORDER_NEW_MESSAGE = 'order-new-message';

    case STAGE_STATUS_CHANGED = 'stage-status-changed';
}
