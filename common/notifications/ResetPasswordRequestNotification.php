<?php

namespace common\notifications;

use common\enums\AppParams;
use common\models\AR\User;
use Exception;
use Yii;

class ResetPasswordRequestNotification implements \common\base\interfaces\notifier\EmailNotificationInterface
{
    /**
     * @param User $user
     */
    public function __construct(public User $user)
    {
    }

    /**
     * @return string|null
     */
    public function getHtml(): string|null
    {
        return 'passwordResetToken-html';
    }

    /**
     * @return string|null
     */
    public function getText(): string|null
    {
        return null;
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