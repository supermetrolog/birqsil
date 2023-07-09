<?php

namespace common\base\model;

use common\base\exception\ValidateException;
use yii\base\Model;

class Form extends Model
{
    /**
     * @return string
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * @return void
     * @throws ValidateException
     */
    public function ifNotValidThrow(): void
    {
        if (!$this->validate()){
            throw new ValidateException($this);
        }
    }
}