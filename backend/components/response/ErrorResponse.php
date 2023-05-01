<?php

namespace backend\components\response;

use common\helpers\HttpCode;

class ErrorResponse
{
    public string $name;
    public string $message;
    public int $status;
    public string $statusText;

    public function __construct(
        string $name,
        string $message,
        HttpCode $status
    )
    {
        $this->name = $name;
        $this->message = $message;
        $this->status = $status->value;
        $this->statusText = $status->toString();
    }
}