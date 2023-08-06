<?php

namespace common\components;

use common\enums\AppParams;
use Exception;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class Param extends Component
{
    /**
     * @param AppParams $name
     * @return mixed
     * @throws InvalidConfigException
     */
    public function get(AppParams $name): mixed
    {
        if (!ArrayHelper::keyExists($name->value, Yii::$app->params)) {
            throw new InvalidConfigException('Param "' . $name->value . ' not exist');
        }

        return Yii::$app->params[$name->value];
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function set(string $name, mixed $value): void
    {
        Yii::$app->params[$name] = $value;
    }
}