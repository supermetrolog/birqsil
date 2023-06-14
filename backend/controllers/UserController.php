<?php

namespace backend\controllers;

use backend\DTO\UserDto;
use common\models\AR\User;
use yii\web\NotFoundHttpException;

class UserController extends AppController
{
    protected array $exceptAuthActions = ['check-email-exists'];
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

    /**
     * @param string $token
     * @return UserDto
     * @throws NotFoundHttpException
     */
    public function actionFindByToken(string $token): UserDto
    {
        $model = User::find()->active()->byAccessToken($token)->one();

        if (!$model) {
            throw new NotFoundHttpException('Not found');
        }

        return new UserDto($model);
    }

}