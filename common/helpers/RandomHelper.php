<?php

namespace common\helpers;

use Yii;
use yii\base\Exception;

class RandomHelper
{
    /**
     * @param int $length
     * @return string
     * @throws Exception
     */
    public static function randomString(int $length): string
    {
        return Yii::$app->security->generateRandomString($length);
    }
}