<?php

namespace backend\models\form;

use common\base\exception\ValidateException;
use common\base\interfaces\notifier\NotifierInterface;
use common\base\model\Form;
use common\enums\UserStatus;
use common\models\AR\User;
use common\notifications\ResetPasswordRequestNotification;
use Yii;
use yii\base\Exception;

class ResetPasswordRequestForm extends Form
{
    public string|null $email = null;

    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier, array $config = [])
    {
        parent::__construct($config);
        $this->notifier = $notifier;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::class,
                'filter' => ['status' => UserStatus::Active->value],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * @return void
     * @throws Exception
     * @throws ValidateException
     */
    public function sendEmail(): void
    {
        $this->ifNotValidThrow();

        $user = User::find()->byEmail($this->email)->active()->one();
        $this->generatePasswordResetToken($user);
        $this->notifier->notify($user, new ResetPasswordRequestNotification($user));
    }

    /**
     * @param User $user
     * @return void
     * @throws ValidateException
     * @throws Exception
     * @throws \Exception
     */
    private function generatePasswordResetToken(User $user): void
    {
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            $user->saveOrThrow();
        }
    }
}