<?php

declare(strict_types=1);

namespace app\tests\unit\forms\user;

use app\forms\user\UsernameForm;
use Codeception\Test\Unit;
use common\models\user\UsernameType;

class UsernameFormTest extends Unit
{
    public function testValidate(): void
    {
        $testCases = [
            [
                'data' => [
                    'username' => '79216667272'
                ],
                'expectedValidate' => true,
                'expectedErrors' => []
            ],
            [
                'data' => [
                    'username' => '792166672722'
                ],
                'expectedValidate' => false,
                'expectedErrors' => [
                    'username' => ['Username is invalid.']
                ]
            ],
            [
                'data' => [
                    'username' => '69015916833'
                ],
                'expectedValidate' => false,
                'expectedErrors' => [
                    'username' => ['Username is invalid.']
                ]
            ],
            [
                'data' => [
                    'username' => '79s15916833'
                ],
                'expectedValidate' => false,
                'expectedErrors' => [
                    'username' => ['Username is invalid.']
                ]
            ],
            [
                'data' => [
                    'username' => 'asdawdd'
                ],
                'expectedValidate' => false,
                'expectedErrors' => [
                    'username' => ['Username is invalid.']
                ]
            ],
            [
                'data' => [
                    'username' => '79216667272@mail.com'
                ],
                'expectedValidate' => true,
                'expectedErrors' => []
            ],
            [
                'data' => [
                    'username' => 'sdawdwad@mail.com'
                ],
                'expectedValidate' => true,
                'expectedErrors' => []
            ],
            [
                'data' => [
                    'username' => 'sdawdwad@mail.ru'
                ],
                'expectedValidate' => true,
                'expectedErrors' => []
            ],
            [
                'data' => [
                    'username' => 'sdawdwad@mail.r'
                ],
                'expectedValidate' => true,
                'expectedErrors' => []
            ],
            [
                'data' => [
                    'username' => 'sdawdwad@m.ru'
                ],
                'expectedValidate' => true,
                'expectedErrors' => []
            ],
            [
                'data' => [
                    'username' => ''
                ],
                'expectedValidate' => false,
                'expectedErrors' => [
                    'username' => ['Username cannot be blank.']
                ]
            ],
            [
                'data' => [],
                'expectedValidate' => false,
                'expectedErrors' => [
                    'username' => ['Username cannot be blank.']
                ]
            ],
        ];

        foreach ($testCases as $tc) {
            $form = new UsernameForm();
            $form->load($tc['data'], '');
            verify($form->validate())->equals(
                $tc['expectedValidate'],
                'validate error: ' . implode(', ', $tc['data'])
            );
            codecept_debug($form->getErrors());
            verify($form->getErrors())->equals($tc['expectedErrors']);
        }
    }

    public function testGetType(): void
    {
        $form = new UsernameForm();
        $form->load(['username' => '72312323322'], '');

        verify($form->getType())->equals(UsernameType::Phone);
        $form->load(['username' => 'nigga@mail.ru'], '');
        verify($form->getType())->equals(UsernameType::Email);
    }
}
