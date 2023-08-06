<?php

namespace common\base\exception;

use Exception;
use yii\base\Model;

class ModelException extends Exception
{
    /**
     * @param Model $model
     * @param string $message
     */
    public function __construct(private readonly Model $model, $message = 'Model error')
    {
        parent::__construct($message);
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }
}