<?php

namespace common\enums;

enum AppParams: string
{
    case SENDER_EMAIL = 'senderEmail';
    case USER_PASSWORD_MIN = 'user.passwordMin';
    case USER_PASSWORD_MAX = 'user.passwordMax';
    case USER_PASSWORD_RESET_TOKEN_EXPIRE = 'user.passwordResetTokenExpire';

    case USER_ACCESS_TOKEN_EXPIRE = 'user.access_token_expire';
    case UPLOAD_FILE_BASE_PATH = 'file.upload_file_base_path';
}
