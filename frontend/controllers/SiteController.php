<?php

namespace frontend\controllers;

use common\actions\ErrorAction;

class SiteController extends AppController
{
    public function actionIndex(): void
    {

    }

    /**
     * @return array[]
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }
}