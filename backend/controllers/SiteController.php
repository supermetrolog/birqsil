<?php

namespace backend\controllers;

use backend\models\form\SignUpForm;
use common\actions\ErrorAction;
use common\base\exception\ValidateHttpException;
use common\components\Notifier;
use common\models\AR\UserAccessToken;
use common\services\UserService;
use Throwable;
use Yii;
use yii\base\Exception;

class SiteController extends AppController
{
    private UserService $userService;

    public function __construct($id, $module, UserService $userService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->userService = $userService;
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * @return UserAccessToken
     * @throws ValidateHttpException
     * @throws Throwable
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function actionSignup(): UserAccessToken
    {
        $form = new SignUpForm();
        $form->load($this->request->post());
        return $this->userService->signUp($form);
    }
}
