<?php

declare(strict_types=1);

namespace common\base\exception;

use Exception;
use yii\base\Model;

class ValidateException extends Exception
{
    public function __construct(private readonly Model $model, string $message = '')
    {
        parent::__construct($message);
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
