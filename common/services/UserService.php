<?php

namespace common\services;

use backend\models\form\SignInForm;
use backend\models\form\SignUpForm;
use common\base\exception\ValidateException;
use common\base\interfaces\notifier\NotifierInterface;
use common\components\Param;
use common\enums\AppParams;
use common\enums\Status;
use common\models\AR\User;
use common\models\AR\UserAccessToken;
use common\notifications\VerifyEmailNotification;
use Throwable;
use yii\base\Exception;
use yii\db\Connection;

class UserService
{
    public function __construct(
        private readonly NotifierInterface $notifier,
        private readonly Param    $params,
        private readonly Connection $db
    )
    {
    }

    /**
     * @param SignUpForm $form
     * @return UserAccessToken
     * @throws ValidateException
     * @throws Exception
     * @throws \yii\db\Exception
     * @throws Throwable
     */
    public function signUp(SignUpForm $form): UserAccessToken
    {
        $form->ifNotValidThrow();

        $tx = $this->db->beginTransaction();

        try {
            $user = new User();
            $user->email = $form->email;
            $user->generateAuthKey();
            $user->generatePasswordResetToken();
            $user->generateVerificationToken();
            $user->setPassword($form->password);

            $user->saveOrThrow();

            $accessToken = $this->createAccessToken(
                $user->id,
                $this->params->get(AppParams::USER_ACCESS_TOKEN_EXPIRE)
            );

            $this->notifier->notify(
                $user,
                new VerifyEmailNotification($user)
            );

            $tx->commit();
            return $accessToken;
        } catch (Throwable $th) {
            $tx->rollBack();
            throw $th;
        }
    }

    /**
     * @param SignInForm $form
     * @return UserAccessToken
     * @throws Exception
     * @throws ValidateException
     * @throws \Exception
     */
    public function signIn(SignInForm $form): UserAccessToken
    {
        $form->ifNotValidThrow();

        return $this->createAccessToken(
            $form->getUser()->id,
            $this->params->get(AppParams::USER_ACCESS_TOKEN_EXPIRE)
        );
    }

    /**
     * @param int $userId
     * @param int $expire
     * @return UserAccessToken
     * @throws ValidateException
     * @throws Exception
     * @throws Exception
     */
    public function createAccessToken(int $userId, int $expire): UserAccessToken
    {
        $accessToken = new UserAccessToken();

        $accessToken->user_id = $userId;
        $accessToken->status = Status::Active->value;
        $accessToken->expire = $expire;
        $accessToken->generateToken();

        $accessToken->saveOrThrow();

        return $accessToken;
    }
}