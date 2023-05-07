<?php

namespace backend\controllers;

use backend\models\DTO\SignUpResponseDto;
use backend\models\form\SignUpForm;
use common\actions\ErrorAction;
use common\base\exception\ValidateException;
use common\base\exception\ValidateHttpException;
use common\models\AR\UserAccessToken;
use common\services\UserService;
use Throwable;
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
     * @return SignUpResponseDto
     * @throws Exception
     * @throws Throwable
     * @throws ValidateException
     * @throws \yii\db\Exception
     */
    public function actionSignup(): SignUpResponseDto
    {
        $form = new SignUpForm();
        $form->load($this->request->post());
        $accessToken = $this->userService->signUp($form);

        return new SignUpResponseDto($accessToken);
    }
}
