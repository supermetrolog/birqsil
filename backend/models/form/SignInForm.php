<?php

namespace app\models\form;

use common\base\exception\ValidateException;
use common\enums\AppParams;
use common\enums\Status;
use common\models\AR\User;
use common\models\AR\UserAccessToken;
use Yii;
use yii\base\Exception;

class SignInForm extends \common\base\model\Form
{
    public string|null $email = null;
    public string|null $password = null;

    private User|null $user = null;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['password', 'validatePassword']
        ];
    }

    /**
     * @param string $attribute
     * @return void
     */
    public function validatePassword(string $attribute): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * @return string
     * @throws ValidateException
     * @throws Exception
     * @throws \Exception
     */
    public function signIn(): string
    {
        $this->ifNotValidThrow();

        $accessToken = new UserAccessToken();

        $accessToken->user_id = $this->user->id;
        $accessToken->status = Status::Active->value;
        $accessToken->expire = Yii::$app->param->get(AppParams::USER_ACCESS_TOKEN_EXPIRE);
        $accessToken->generateToken();

        $accessToken->saveOrThrow();

        return $accessToken->token;
    }

    /**
     * @return User|null
     */
    protected function getUser(): User|null
    {
        if (!$this->user) {
            $this->user = User::findByEmail($this->email);
        }

        return $this->user;
    }
}