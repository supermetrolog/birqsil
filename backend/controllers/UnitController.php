<?php

declare(strict_types=1);

namespace backend\controllers;

use common\models\AR\Unit;

class UnitController extends AppController
{
    /**
     * @return Unit[]
     */
    public function actionIndex(): array
    {
        return Unit::find()->all();
    }
}