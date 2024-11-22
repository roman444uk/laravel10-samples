<?php

namespace App\Enums\System;

enum SessionEnum: string
{
    case CHAT_OFF_CANVAS_WIDTH = 'chat-off-canvas-width';

    case REGISTER_EMAIL_CONFIRMATION_CODE = 'register-email-confirmation-code';
    case REGISTER_EMAIL_CONFIRMATION_CODE_ADDRESS = 'register-email-confirmation-code-address';

    case LOGIN_AS_USER_LOGGED_ID = '__logged-user-id';
    case LOGIN_AS_USER_LOGGED_PAGE = '__logged-user-page';
}
