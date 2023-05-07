<?php

namespace backend\tests\unit\models\form;

use backend\models\form\ResetPasswordForm;
use backend\models\form\ResetPasswordRequestForm;
use Codeception\Test\Unit;
use common\base\exception\ValidateException;
use common\base\interfaces\notifier\NotifierInterface;
use common\fixtures\UserFixture;
use common\models\AR\User;
use PHPUnit\Framework\MockObject\MockObject;

class ResetPasswordRequestFormTest extends Unit
{
    private NotifierInterface|MockObject $notifier;

    public function setUp(): void
    {
        parent::setUp();
        $this->notifier = $this->createMock(NotifierInterface::class);
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
                'desc' => 'Invalid email',
                'data' => [
                    'email' => 'testtest.test',
                ],
                'isValid' => false,
            ],
            [
                'desc' => 'Inactive user email',
                'data' => [
                    'email' => 'test@test.test',
                ],
                'isValid' => false,
            ],
            [
                'desc' => 'Valid email',
                'data' => [
                    'email' => 'test2@test.test',
                ],
                'isValid' => true,
            ],
        ];

        foreach ($testCases as $tc) {
            $form = $this->getForm();
            $form->load($tc['data']);
            verify($form->validate())->equals($tc['isValid'], json_encode($form->getErrors()));
            verify($form->validate())->equals($tc['isValid'], $tc['desc']);
        }
    }

    private function getForm(): ResetPasswordRequestForm
    {
        return new ResetPasswordRequestForm($this->notifier);
    }

    public function testSendEmailInvalid(): void
    {
        $form = $this->getForm();
        $form->load([
            'email' => '123',
        ]);
        $this->expectException(ValidateException::class);
        $form->sendEmail();
    }

    public function testSendEmailValid(): void
    {
        $form = $this->getForm();
        $form->load([
            'email' => 'test2@test.test',
        ]);
        $this->notifier->method('notify');
        $form->sendEmail();
    }
}