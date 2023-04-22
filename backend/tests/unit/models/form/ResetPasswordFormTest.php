<?php

namespace app\tests\unit\models\form;

use app\models\form\ResetPasswordForm;
use Codeception\Test\Unit;
use common\base\exception\ValidateException;
use common\fixtures\UserFixture;
use common\models\AR\User;

class ResetPasswordFormTest extends Unit
{
    private const PASSWORD_RESET_TOKEN = 'RkD_Jw0_8HEedzLk7MM-ZKEFfYR7VbMr_1682194998';
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
                    'token' => self::PASSWORD_RESET_TOKEN,
                    'password' => 'test423423',
                ],
                'isValid' => true,
            ],
            [
                'desc' => 'Invalid token',
                'data' => [
                    'token' => 'invalid',
                    'password' => 'test',
                ],
                'isValid' => false,
            ],
        ];

        foreach ($testCases as $tc) {
            $form = new ResetPasswordForm();
            $form->load($tc['data']);
            verify($form->validate())->equals($tc['isValid'], $tc['desc']);
        }
    }

    public function testResetValid(): void
    {
        $form = new ResetPasswordForm();
        $form->token = self::PASSWORD_RESET_TOKEN;
        $form->password = 'password_0';

        $user = User::find()->where(['password_reset_token' => self::PASSWORD_RESET_TOKEN])->one();
        $beforeAuthKey = $user->auth_key;
        $beforePasswordHash = $user->password_hash;

        $form->reset();

        $user->refresh();

        verify($user->password_reset_token)->null();
        verify($user->getAuthKey())->notEquals($beforeAuthKey);
        verify($user->password_hash)->notEquals($beforePasswordHash);
    }

    public function testVerifyInvalid(): void
    {
        $form = new ResetPasswordForm();
        $form->token = 'invalid';

        $this->expectException(ValidateException::class);
        $form->reset();
    }
}