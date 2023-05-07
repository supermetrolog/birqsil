<?php

namespace backend\controllers;

use backend\models\DTO\CredentialDto;
use backend\models\form\ResetPasswordForm;
use backend\models\form\ResetPasswordRequestForm;
use backend\models\form\SignInForm;
use backend\models\form\SignUpForm;
use backend\models\form\VerifyEmailForm;
use common\actions\ErrorAction;
use common\base\exception\ValidateException;
use common\base\interfaces\notifier\NotifierInterface;
use common\helpers\HttpCode;
use common\services\UserService;
use Throwable;
use yii\base\Exception;

class SiteController extends AppController
{
    private UserService $userService;
    private NotifierInterface $notifier;

    public function __construct(
        $id,
        $module,
        UserService $userService,
        NotifierInterface $notifier,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->userService = $userService;
        $this->notifier = $notifier;
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
     * @return CredentialDto
     * @throws Exception
     * @throws Throwable
     * @throws ValidateException
     * @throws \yii\db\Exception
     */
    public function actionSignup(): CredentialDto
    {
        $form = new SignUpForm();
        $form->load($this->request->post());
        $accessToken = $this->userService->signUp($form);

        return new CredentialDto($accessToken);
    }

    /**
     * @return CredentialDto
     * @throws Exception
     * @throws ValidateException
     */
    public function actionSignin(): CredentialDto
    {
        $form = new SignInForm();
        $form->load($this->request->post());
        $accessToken = $this->userService->signIn($form);

        return new CredentialDto($accessToken);
    }

    /**
     * @return void
     * @throws ValidateException
     */
    public function actionVerifyEmail(): void
    {
        $form = new VerifyEmailForm();
        $form->load($this->request->getQueryParams());
        $form->verify();
        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ValidateException
     */
    public function actionResetPassword(): void
    {
        $form = new ResetPasswordForm();
        $form->load($this->request->post());
        $form->reset();
        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ValidateException
     */
    public function actionResetPasswordRequest(): void
    {
        $form = new ResetPasswordRequestForm($this->notifier);
        $form->load($this->request->post());
        $form->sendEmail();
        $this->response->setStatusCode(HttpCode::NO_CONTENT->value);
    }
}
