<?php

namespace common\components;

use common\base\interfaces\notifier\EmailNotificationInterface;
use common\base\interfaces\notifier\NotifierInterface;
use common\base\interfaces\notifier\TelegramNotificationInterface;
use common\models\AR\User;
use yii\base\ErrorException;
use yii\db\Exception;
use yii\mail\MailerInterface;

readonly class Notifier implements NotifierInterface
{
    /**
     * @param MailerInterface $mailer
     */
    public function __construct(private MailerInterface $mailer)
    {
    }

    /**
     * @param User $user
     * @param EmailNotificationInterface|TelegramNotificationInterface $notification
     * @return void
     * @throws ErrorException
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
     * @throws ErrorException
     * @throws Exception
     */
    private function sendToEmail(User $user, EmailNotificationInterface $notification): void
    {
        $res = $this->mailer
            ->compose(
                $this->getHtmlOrText($notification),
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

    /**
     * @param EmailNotificationInterface $notification
     * @return array
     * @throws ErrorException
     */
    private function getHtmlOrText(EmailNotificationInterface $notification): array
    {
        if ($html = $notification->getHtml()) {
            return ['html' => $html];
        }

        if ($text = $notification->getText()) {
            return ['text' => $text];
        }

        throw new ErrorException('Html or text cannot be blank');
    }
}