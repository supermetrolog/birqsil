<?php

namespace common\helpers;

enum HttpCode: int
{
    case OK = 200;
    case NO_CONTENT = 204;
    case NOT_FOUND = 404;
    case BAD_REQUEST = 400;
    case INTERNAL_SERVER_ERROR = 500;
    case VALIDATE_ERROR = 422;

    case METHOD_NOT_ALLOWED = 405;
    public function toString(): string
    {
        return match($this) {
            self::OK => 'OK',
            self::NOT_FOUND => 'Not found',
            self::BAD_REQUEST => 'Bad request',
            self::INTERNAL_SERVER_ERROR => 'Internal server error',
            self::VALIDATE_ERROR => 'Validate error',
            self::METHOD_NOT_ALLOWED => 'Method not allowed',
            default => 'Unknown error'
        };
    }
}
