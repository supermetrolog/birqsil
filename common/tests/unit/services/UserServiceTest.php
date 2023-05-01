<?php

namespace common\tests\unit\services;

use backend\models\form\SignUpForm;
use Codeception\Test\Unit;
use common\base\exception\ValidateException;
use common\base\interfaces\notifier\NotifierInterface;
use common\components\Param;
use common\enums\AppParams;
use common\enums\UserStatus;
use common\models\AR\User;
use common\services\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use Throwable;
use Yii;
use yii\base\Exception;

class UserServiceTest extends Unit
{
    private NotifierInterface|MockObject $notifier;
    private Param|MockObject $param;

    public function _before(): void
    {
        $this->notifier = $this->createMock(NotifierInterface::class);
        $this->param = $this->createMock(Param::class);
    }

    /**
     * @return UserService
     */
    private function getService(): UserService
    {
        return new UserService($this->notifier, $this->param, Yii::$app->db);
    }

    /**
     * @return void
     * @throws Exception
     * @throws Throwable
     * @throws ValidateException
     * @throws \yii\db\Exception
     */
    public function testSignUpInvalid(): void
    {
        $service = $this->getService();
        $form = new SignUpForm($service);

        $form->load([
            'email' => 'email@email.ru',
            'password' => 'password1',
            'passwordRepeat' => 'password'
        ]);

        $this->expectException(ValidateException::class);
        $service->signup($form);
    }

    /**
     * @return void
     * @throws Exception
     * @throws Throwable
     * @throws ValidateException
     * @throws \yii\db\Exception
     */
    public function testSignupValid(): void
    {
        $service = $this->getService();

        $form = new SignUpForm($service);
        $form->load([
            'email' => 'email@email.ru',
            'password' => 'password1',
            'passwordRepeat' => 'password1'
        ]);

        $this->notifier->method('notify');
        $this->param->method('get')
            ->with(AppParams::USER_ACCESS_TOKEN_EXPIRE)
            ->willReturn(3600 * 24);

        $accessToken = $service->signup($form);

        $user = User::find()->byEmail('email@email.ru')->one();
        verify($user)->notNull();
        verify($user->status)->equals(UserStatus::Inactive->value);
        verify($user->accessTokens)->notNull()->notEmpty();
        verify($accessToken->user_id)->equals($user->id);
    }
}