<?php

declare(strict_types=1);

namespace app\tests\unit\forms\user;

use app\forms\user\SignUpForm;
use Codeception\Test\Unit;
use common\fixtures\UserFixture;

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
                'data' => [
                    'username' => [
                        'username' => 'nigger'
                    ],
                    'firstName' => 'name'
                ],
                'expectedValidate' => false,
                'expectedErrors' => [
                    'username' => ['Username is invalid.']
                ]
            ],
            [
                'data' => [
                    'firstName' => 'n'
                ],
                'expectedValidate' => false,
                'expectedErrors' => [
                    'firstName' => ['First Name should contain at least 2 characters.'],
                    'username' => ['Username cannot be blank.']
                ]
            ],
            [
                'data' => [
                    'username' => '79334445566',
                    'firstName' => 'name'
                ],
                'expectedValidate' => true,
                'expectedErrors' => []
            ],
            [
                'data' => [
                    'username' => '7933444556',
                    'firstName' => 'name'
                ],
                'expectedValidate' => false,
                'expectedErrors' => [
                    'username' => ['Username is invalid.']
                ]
            ],
            [
                'data' => [
                    'username' => '79216667272',
                    'firstName' => 'name'
                ],
                'expectedValidate' => false,
                'expectedErrors' => [
                    'username' => ['Username "79216667272" has already been taken.']
                ]
            ],
        ];

        foreach ($testCases as $tc) {
            $form = new SignUpForm();
            $form->load($tc['data'], '');
            verify($form->validate())->equals($tc['expectedValidate']);
            verify($form->getErrors())->equals($tc['expectedErrors']);
        }
    }
}
