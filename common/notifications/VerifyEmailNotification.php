<?php

namespace common\notifications;

use common\base\interfaces\notifier\EmailNotificationInterface;
use common\enums\AppParams;
use Exception;
use Yii;
use yii\base\BaseObject;

class VerifyEmailNotification extends BaseObject implements EmailNotificationInterface
{
    public string $token;

    public function getHtml(): string
    {
        return '<b>html</b>';
    }

    public function getText(): string
    {
        return 'Token: ' . $this->token;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getFromEmail(): string
    {
        return Yii::$app->param->get(AppParams::SENDER_EMAIL);
    }

    public function getFromName(): string
    {
        return 'Birqsil';
    }

    public function getSubject(): string
    {
        return 'subject';
    }
}