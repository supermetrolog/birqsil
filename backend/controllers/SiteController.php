<?php

namespace backend\controllers;

use common\actions\ErrorAction;
use yii\rest\Controller;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }
}
