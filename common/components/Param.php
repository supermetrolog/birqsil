<?php

namespace common\components;

use common\enums\AppParams;
use Exception;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class Param extends Component
{
    /**
     * @param AppParams $name
     * @return mixed
     * @throws Exception
     */
    public function get(AppParams $name): mixed
    {
        if (!ArrayHelper::keyExists($name->value, Yii::$app->params)) {
            throw new Exception('Param "' . $name->value . ' not exist');
        }

        return Yii::$app->params[$name->value];
    }
}