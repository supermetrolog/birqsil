<?php

namespace backend\controllers;

use common\actions\ErrorAction;
use Exception;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'index',
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function actionIndex()
    {
        throw new Exception("WDAWD");
    }
}
