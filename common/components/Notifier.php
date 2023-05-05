<?php

namespace common\components;

use common\base\interfaces\notifier\EmailNotificationInterface;
use common\base\interfaces\notifier\NotifierInterface;
use common\base\interfaces\notifier\TelegramNotificationInterface;
use common\models\AR\User;
use yii\db\Exception;
use yii\mail\MailerInterface;

class Notifier implements NotifierInterface
{
    /**
     * @param MailerInterface $mailer
     */
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    /**
     * @param User $user
     * @param EmailNotificationInterface|TelegramNotificationInterface $notification
     * @return void
     * @throws Exception
     */
    public function notify(
        User $user,
        EmailNotificationInterface|TelegramNotificationInterface $notification
    ): void
    {
        if ($notification instanceof EmailNotificationInterface) {
            $this->sendToEmail($user, $notification);
        } else {
            $this->sendToTelegram($user, $notification);
        }
    }

    /**
     * @param User $user
     * @param TelegramNotificationInterface $notification
     * @return void
     */
    private function sendToTelegram(User $user, TelegramNotificationInterface $notification): void
    {
        $notification->send();
    }

    /**
     * @param User $user
     * @param EmailNotificationInterface $notification
     * @return void
     * @throws Exception
     */
    private function sendToEmail(User $user, EmailNotificationInterface $notification): void
    {
        $res = $this->mailer
            ->compose(
                ['html' => $notification->getHtml(), 'text' => $notification->getText()],
                $notification->getParams()
            )
            ->setFrom([$notification->getFromEmail() => $notification->getFromName()])
            ->setTo($user->email)
            ->setSubject($notification->getSubject())
            ->send();

        if (!$res) {
            throw new Exception('Email send error');
        }
    }
}