<?php

namespace backend\controllers;

use common\models\AR\User;

class UserController extends AppController
{
    /**
     * @return array
     */
    public function actionCheckEmailExists(): array
    {
        return [
            'exists' => User::find()
                ->byEmail($this->request->get('email', ''))
                ->exists()
        ];
    }
}