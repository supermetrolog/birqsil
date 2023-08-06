<?php

declare(strict_types=1);

namespace common\base\exception;

use Exception;
use yii\base\Model;

class ValidateException extends ModelException
{
    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        parent::__construct($model, 'Validate error');
    }
}
