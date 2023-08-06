<?php

namespace common\base\exception;

use yii\base\Model;

class SaveModelException extends ModelException
{
    public function __construct(Model $model)
    {
        parent::__construct($model, 'Save model error: ' . json_encode($model->getErrors()));
    }
}