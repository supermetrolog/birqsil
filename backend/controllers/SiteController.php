<?php

namespace backend\controllers;

use common\actions\ErrorAction;

class SiteController extends AppController
{
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