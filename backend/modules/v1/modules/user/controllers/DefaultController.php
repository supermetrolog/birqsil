<?php

namespace app\modules\v1\modules\user\controllers;

use common\models\User;
use yii\rest\ActiveController;

class DefaultController extends ActiveController
{
    public $modelClass = User::class;
}
