<?php

namespace app\tests\unit\models\form;

use app\models\form\SignUpForm;
use Codeception\Test\Unit;
use common\base\exception\ValidateException;
use common\base\interfaces\notifier\NotifierInterface;
use common\enums\UserStatus;
use common\fixtures\UserFixture;
use common\models\AR\User;
use PHPUnit\Framework\MockObject\MockObject;
use yii\base\Exception;

class SignUpFormTest extends Unit
{
    private NotifierInterface|MockObject $notifier;

    public function _before(): void
    {
        $this->notifier = $this->createMock(NotifierInterface::class);
    }

    private function getForm(): SignUpForm
    {
        return new SignUpForm($this->notifier);
    }

    public function _fixtures(): array
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ];
    }

    public function testValidate(): void
    {
        $testCases = [
            [
                'desc' => 'Valid all',
                'data' => [
                    'email' => 'email@email.ru',
                    'password' => 'password1',
                    'passwordRepeat' => 'password1',
                ],
                'isValid' => true,
            ],
            [
                'desc' => 'Invalid email',
                'data' => [
                    'email' => 'test.ru',
                    'password' => 'password1',
                    'passwordRepeat' => 'password1',
                ],
                'isValid' => false,
            ],
            [
                'desc' => 'Invalid repeat password',
                'data' => [
                    'email' => 'email@email.ru',
                    'password' => 'password1',
                    'passwordRepeat' => 'password12',
                ],
                'isValid' => false,
            ],
            [
                'desc' => 'Email already exists',
                'data' => [
                    'email' => 'test@test.test',
                    'password' => 'password1',
                    'passwordRepeat' => 'password1',
                ],
                'isValid' => false,
            ],
        ];

        foreach ($testCases as $tc) {
            $form = $this->getForm();
            $form->load($tc['data']);
            verify($form->validate())->equals($tc['isValid'], $tc['desc']);
        }
    }

    /**
     * @return void
     * @throws ValidateException
     * @throws Exception
     */
    public function testSignUpInvalid(): void
    {
        $form = $this->getForm();
        $form->load([
            'email' => 'email@email.ru',
            'password' => 'password1',
            'passwordRepeat' => 'password'
        ]);
        $this->expectException(ValidateException::class);
        $form->signUp();
    }

    /**
     * @return void
     * @throws ValidateException
     * @throws Exception
     */
    public function testSignUpValid(): void
    {
        $form = $this->getForm();
        $form->load([
            'email' => 'email@email.ru',
            'password' => 'password1',
            'passwordRepeat' => 'password1'
        ]);

        $this->notifier->method('notify');

        $form->signUp();

        $user = User::find()->byEmail('email@email.ru')->one();
        verify($user)->notNull();
        verify($user->status)->equals(UserStatus::Inactive->value);
    }
}