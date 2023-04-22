<?php

namespace common\tests\unit\components;

use Codeception\Test\Unit;
use common\base\interfaces\notifier\EmailNotificationInterface;
use common\components\Notifier;
use common\models\AR\User;
use PHPUnit\Framework\MockObject\MockObject;
use yii\mail\MailerInterface;
use yii\mail\MessageInterface;

class NotifierTest extends Unit
{
    private MailerInterface|MockObject $mailer;
    private EmailNotificationInterface|MockObject $emailNotification;

    private function getComponent(): Notifier
    {
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->emailNotification = $this->createMock(EmailNotificationInterface::class);

        return new Notifier([
            'mailer' => $this->mailer
        ]);
    }

    public function testNotify(): void
    {
        $c = $this->getComponent();
        $message = $this->createMock(MessageInterface::class);
        $this->mailer->method('compose')->willReturn($message);
        $message->method('setTo')->willReturn($message);
        $message->method('setSubject')->willReturn($message);
        $message->method('send')->willReturn($message);
        $message->method('setFrom')->willReturn($message);

        $this->emailNotification->method('getSubject');
        $this->emailNotification->method('getHtml');
        $this->emailNotification->method('getText');
        $this->emailNotification->method('getFromEmail');
        $this->emailNotification->method('getFromName');

        $stubUser = new User(['email' => 'test@test.test']);

        $c->notify($stubUser, $this->emailNotification);
    }
}