<?php

declare(strict_types=1);

namespace common\forms\exceptions;

use Exception;
use yii\base\Model;

class ValidateException extends Exception
{
    public function __construct(private Model $model)
    {
    }

    public function getModel()
    {
        return $this->model;
    }
}
