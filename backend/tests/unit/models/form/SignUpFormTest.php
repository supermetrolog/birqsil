<?php

namespace app\tests\unit\models\form;

use app\models\form\SignUpForm;
use Codeception\Test\Unit;
use common\base\exception\ValidateException;
use common\fixtures\UserFixture;
use yii\base\Exception;

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
                    'email' => 'nigga@nigga.ru',
                    'password' => 'password1',
                    'passwordRepeat' => 'password1',
                ],
                'isValid' => true,
            ],
            [
                'desc' => 'Invalid email',
                'data' => [
                    'email' => 'nigganigga.ru',
                    'password' => 'password1',
                    'passwordRepeat' => 'password1',
                ],
                'isValid' => false,
            ],
            [
                'desc' => 'Invalid repeat password',
                'data' => [
                    'email' => 'nigga@nigga.ru',
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

    /**
     * @return void
     * @throws ValidateException
     * @throws Exception
     */
    public function testSignUpInvalid(): void
    {
        $form = new SignUpForm();
        $form->load([
            'email' => 'email@email.ru',
            'password' => 'password1',
            'passwordRepeat' => 'password'
        ]);
        $this->expectException(ValidateException::class);
        $form->signUp();
    }
}