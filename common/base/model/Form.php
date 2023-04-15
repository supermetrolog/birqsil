<?php

namespace common\base\model;

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
}