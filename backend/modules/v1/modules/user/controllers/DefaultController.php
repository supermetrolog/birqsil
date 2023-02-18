<?php

namespace app\modules\v1\modules\user\controllers;

use common\models\user\User;
use yii\rest\ActiveController;

class DefaultController extends ActiveController
{
    public $modelClass = User::class;

    private $service;
}
