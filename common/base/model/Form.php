<?php

namespace common\base\model;

use common\base\exception\ValidateException;
use yii\base\Model;

class Form extends Model
{
    /**
     * @param $data
     * @param string $formName
     * @return bool
     */
    public function load($data, $formName = ''): bool
    {
        return parent::load($data, $formName);
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