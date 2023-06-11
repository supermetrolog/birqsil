<?php

namespace backend\controllers;

use common\actions\ErrorAction;
use Yii;
use yii\db\Connection;
use yii\web\User;

class SiteController extends AppController
{
    protected array $exceptAuthActions = ['*'];

    public function __construct($id, $module, Connection $db, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): void
    {
        $c = Yii::$container;

        var_dump($c->get(User::class)->isGuest);die;
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