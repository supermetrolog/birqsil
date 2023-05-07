<?php

namespace common\notifications;

use common\base\interfaces\notifier\EmailNotificationInterface;
use common\enums\AppParams;
use common\models\AR\User;
use Exception;
use Yii;
use yii\base\BaseObject;

class VerifyEmailNotification implements EmailNotificationInterface
{
    /**
     * @param User $user
     */
    public function __construct(public User $user)
    {
    }

    /**
     * @return User[]
     */
    public function getParams(): array
    {
        return ['user' => $this->user];
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return 'emailVerify-html';
    }

    /**
     * @return string|null
     */
    public function getText(): string|null
    {
        return null;
    }


    /**
     * @return string
     * @throws Exception
     */
    public function getFromEmail(): string
    {
        return Yii::$app->param->get(AppParams::SENDER_EMAIL);
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return 'Birqsil';
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return 'subject';
    }
}