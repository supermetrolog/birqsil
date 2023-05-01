<?php

namespace backend\tests\unit\models\form;

use backend\models\form\SignUpForm;
use Codeception\Test\Unit;
use common\fixtures\UserFixture;
use common\services\UserService;
use PHPUnit\Framework\MockObject\MockObject;

class SignUpFormTest extends Unit
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
            $form = new SignUpForm();
            $form->load($tc['data']);
            verify($form->validate())->equals($tc['isValid'], $tc['desc']);
        }
    }
}