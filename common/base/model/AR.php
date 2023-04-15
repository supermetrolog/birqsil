<?php

namespace common\base\model;

use common\base\exception\ValidateException;
use yii\db\ActiveRecord;

class AR extends ActiveRecord
{
    /**
     * @param bool $validate
     * @return void
     * @throws ValidateException
     */
    public function saveOrThrow(bool $validate = true): void
    {
        if (!$this->save($validate)) {
            throw new ValidateException($this);
        }
    }
}