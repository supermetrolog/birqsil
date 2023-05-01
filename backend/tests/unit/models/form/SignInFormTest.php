<?php

namespace backend\tests\unit\models\form;

use backend\models\form\SignInForm;
use Codeception\Test\Unit;
use common\fixtures\UserFixture;

class SignInFormTest extends Unit
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
            $form = new SignInForm();
            $form->load($tc['data']);
            verify($form->validate())->equals($tc['isValid'], $tc['desc']);
        }
    }
}