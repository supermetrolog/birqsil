<?php

namespace app\tests\unit\models\form;

use backend\models\form\SignUpForm;
use Codeception\Test\Unit;
use common\base\exception\ValidateException;
use common\fixtures\UserFixture;
use common\services\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use Throwable;
use yii\base\Exception;

class SignUpFormTest extends Unit
{
    private UserService|MockObject $service;

    public function _before(): void
    {
        $this->service = $this->createMock(UserService::class);
    }

    private function getForm(): SignUpForm
    {
        return new SignUpForm($this->service);
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
}