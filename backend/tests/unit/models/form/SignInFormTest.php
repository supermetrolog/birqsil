<?php

namespace app\tests\unit\models\form;

use app\models\form\SignInForm;
use common\base\exception\ValidateException;
use common\enums\Status;
use common\fixtures\UserFixture;
use common\models\AR\User;
use common\models\AR\UserAccessToken;

class SignInFormTest extends \Codeception\Test\Unit
{

    private function getForm(): SignInForm
    {
        return new SignInForm();
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
                'desc' => 'Valid',
                'data' => [
                    'email' => 'test@test.test',
                    'password' => 'password_0',
                ],
                'isValid' => true,
            ],
            [
                'desc' => 'Invalid email',
                'data' => [
                    'email' => 'test@test.tes',
                    'password' => 'password_0',
                ],
                'isValid' => false,
            ],
            [
                'desc' => 'Invalid password',
                'data' => [
                    'email' => 'test@test.test',
                    'password' => 'invalid_password',
                ],
                'isValid' => false,
            ],
            [
                'desc' => 'Null email and password',
                'data' => [
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

    public function testSignInValid(): void
    {
        $form = $this->getForm();
        $form->email = 'test@test.test';
        $form->password = 'password_0';

        $token = $form->signIn();

        $model = UserAccessToken::find()->byToken($token)->one();
        verify($model->user->email)->equals($form->email);
        verify($model->status)->equals(Status::Active->value);
        verify($model->user_id)->equals(User::findByEmail('test@test.test')->id);
    }

    public function testSignInInvalid(): void
    {
        $form = $this->getForm();
        $form->email = 'invalid email';
        $this->expectException(ValidateException::class);
        $form->signIn();
    }
}