<?php

namespace app\tests\unit\models\form;

use app\models\form\VerifyEmailForm;
use Codeception\Test\Unit;
use common\base\exception\ValidateException;
use common\enums\UserStatus;
use common\fixtures\UserFixture;
use common\models\AR\User;

class VerifyEmailFormTest extends Unit
{
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
                'desc' => 'Valid token',
                'data' => [
                    'token' => 'valid',
                ],
                'isValid' => true,
            ],
            [
                'desc' => 'Invalid token',
                'data' => [
                    'token' => 'invalid',
                ],
                'isValid' => false,
            ],
        ];

        foreach ($testCases as $tc) {
            $form = new VerifyEmailForm();
            $form->load($tc['data']);
            verify($form->validate())->equals($tc['isValid'], $tc['desc']);
        }
    }

    public function testVerifyValid(): void
    {
        $form = new VerifyEmailForm();
        $form->token = 'valid';

        $user = User::findByVerificationToken('valid');
        verify($user->status)->equals(UserStatus::Inactive->value);

        $form->verify();

        $user = User::find()->where(['verification_token' => 'valid'])->one();
        verify($user->status)->equals(UserStatus::Active->value);
    }

    public function testVerifyInvalid(): void
    {
        $form = new VerifyEmailForm();
        $form->token = 'invalid';

        $this->expectException(ValidateException::class);
        $form->verify();
    }
}