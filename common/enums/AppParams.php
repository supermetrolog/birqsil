<?php

namespace common\enums;

enum AppParams: string
{
    case SENDER_EMAIL = 'senderEmail';
    case USER_PASSWORD_MIN = 'user.passwordMin';
    case USER_PASSWORD_MAX = 'user.passwordMax';
    case USER_PASSWORD_RESET_TOKEN_EXPIRE = 'user.passwordResetTokenExpire';
}
